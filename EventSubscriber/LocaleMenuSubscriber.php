<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventSubscriber;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuToolsEvent;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Languages;
use Symfony\Contracts\Translation\TranslatorInterface;

final class LocaleMenuSubscriber implements EventSubscriberInterface
{
    private TranslatorInterface $translator;
    private RequestStack $requestStack;
    /**
     * @var array<int,string>
     */
    private array $locales;

    public static function getSubscribedEvents(): array
    {
        return [MenuToolsEvent::class => 'createLocaleMenu'];
    }

    /**
     * @param array<array-key,string> $locales
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        array $locales
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
    }

    public function createLocaleMenu(MenuEvent $event): void
    {
        if (2 > count($this->locales)) {
            return;
        }

        $language = new Item('admin-locale');
        $language->setOptions(['attr' => ['id' => 'language']]);
        $language->setLabel(
            $this->translator->trans(
                'admin.language.current',
                ['%locale%' => $this->getLanguageName($this->getCurrentLocale())],
                'FSiAdminBundle'
            )
        );

        foreach ($this->locales as $locale) {
            $localeItem = new RoutableItem(
                "admin-locale.{$locale}",
                'fsi_admin_locale',
                [
                    '_locale' => $locale,
                    'redirect_uri' => $this->getCurrentRequest()->getUri()
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

    private function getLanguageName(string $locale): string
    {
        return Languages::getName($locale, $this->getCurrentLocale());
    }

    private function getCurrentLocale(): string
    {
        return $this->getCurrentRequest()->getLocale();
    }

    private function getCurrentRequest(): Request
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            throw new RuntimeException("No request present when building menu.");
        }

        return $request;
    }
}
