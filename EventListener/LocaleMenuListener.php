<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleMenuListener
{
    /**
     * @var bool
     */
    private $displayLanguageSwitch;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var Request
     */
    private $request;

    public function __construct($displayLanguageSwitch, TranslatorInterface $translator)
    {
        $this->displayLanguageSwitch = $displayLanguageSwitch;
        $this->translator = $translator;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    public function createLocaleMenu(MenuEvent $event)
    {
        if ($this->displayLanguageSwitch == false) {
            return;
        }

        $language = new RoutableItem('admin.language.current');
        $language->setLabel(
            $this->translator->trans(
                'admin.language.current',
                array('%locale%' => $this->request ? $this->request->getLocale() : '---'),
                'FSiAdminBundle'
            )
        );
        $language->setOptions(array('attr' => array('id' => 'language')));
        $language->addChild(new RoutableItem('admin.language.polish', 'fsi_admin_locale', array('_locale' => 'pl')));
        $language->addChild(new RoutableItem('admin.language.english', 'fsi_admin_locale', array('_locale' => 'en')));

        $event->getMenu()->addChild($language);
    }
}
