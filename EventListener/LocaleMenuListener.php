<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuEvents;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Languages;
use Symfony\Contracts\Translation\TranslatorInterface;

class LocaleMenuListener implements EventSubscriberInterface
{
    private TranslatorInterface $translator;

    private RequestStack $requestStack;

    /**
     * @var array<int,string>
     */
    private array $locales;

    public static function getSubscribedEvents(): array
    {
        return [
            MenuEvents::TOOLS => 'createLocaleMenu',
        ];
    }

    /**
     * @param TranslatorInterface $translator
     * @param RequestStack $requestStack
     * @param array<int,string> $locales
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, array $locales)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
    }

    public function createLocaleMenu(MenuEvent $event): void
    {
        if (count($this->locales) < 2) {
            return;
        }

        $language = new Item('admin-locale');
        $language->setLabel(
            $this->translator->trans(
                'admin.language.current',
                ['%locale%' => $this->getLanguageName()],
                'FSiAdminBundle'
            )
        );
        $language->setOptions(['attr' => ['id' => 'language']]);

        foreach ($this->locales as $locale) {
            $localeItem = new RoutableItem(
                sprintf('admin-locale.%s', $locale),
                'fsi_admin_locale',
                [
                    '_locale' => $locale,
                    'redirect_uri' => $this->getMasterRequest()->getUri()
                ]
            );

            $localeItem->setLabel($this->getLanguageName($locale));
            if ($locale === $this->getCurrentLocale()) {
                $localeItem->setOptions(['attr' => ['class' => 'active']]);
            }
            $language->addChild($localeItem);
        }

        $event->getMenu()->addChild($language);
    }

    private function getLanguageName(?string $locale = null): string
    {
        if (null === $locale) {
            $locale = $this->getCurrentLocale();
        }

        return Languages::getName($locale, $this->getCurrentLocale());
    }

    private function getCurrentLocale(): string
    {
        return $this->getMasterRequest()->getLocale();
    }

    private function getMasterRequest(): Request
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            throw new RuntimeException("Batch actions are only available in request context");
        }

        return $request;
    }
}
