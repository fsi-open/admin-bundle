<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleMenuListener
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param array $locales
     */
    public function __construct(TranslatorInterface $translator, RequestStack $requestStack, array $locales)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->locales = $locales;
    }

    public function createLocaleMenu(MenuEvent $event)
    {
        if (count($this->locales) < 2) {
            return;
        }

        $language = new Item('admin-locale');
        $language->setLabel(
            $this->translator->trans(
                'admin.language.current',
                array('%locale%' => $this->getLanguageName()),
                'FSiAdminBundle'
            )
        );
        $language->setOptions(array('attr' => array('id' => 'language')));

        foreach ($this->locales as $locale) {
            $localeItem = new RoutableItem(
                sprintf('admin-locale.%s', $locale),
                'fsi_admin_locale',
                array(
                    '_locale' => $locale,
                    'redirect_uri' => $this->requestStack->getMasterRequest()->getUri()
                )
            );

            $localeItem->setLabel($this->getLanguageName($locale));
            if ($locale === $this->getCurrentLocale()) {
                $localeItem->setOptions(array('attr' => array('class' => 'active')));
            }
            $language->addChild($localeItem);
        }

        $event->getMenu()->addChild($language);
    }

    private function getLanguageName($locale = null)
    {
        if (!$locale) {
            $locale = $this->getCurrentLocale();
        }

        return Intl::getLanguageBundle()
            ->getLanguageName(
                $locale,
                null,
                $this->getCurrentLocale()
            );
    }

    /**
     * @return string
     */
    private function getCurrentLocale()
    {
        return $this->requestStack->getMasterRequest()->getLocale();
    }
}
