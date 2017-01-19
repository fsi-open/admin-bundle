<?php

namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LocaleMenuListenerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    function let($translator, $requestStack)
    {
        $this->beConstructedWith($translator, $requestStack, array('en', 'de'));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\MenuEvent $event
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_should_build_locale_menu($event, $requestStack, $request)
    {
        $menu = new Item();
        $event->getMenu()->willReturn($menu);
        $requestStack->getMasterRequest()->willReturn($request);
        $request->getLocale()->willReturn('de');
        $request->getUri()->willReturn('uri_to_redirect_to');

        $this->createLocaleMenu($event);

        $rootItem = $menu->getChildren()['admin-locale'];

        $localeItems = $rootItem->getChildren();

        $enItem = $localeItems['admin-locale.en'];
        $deItem = $localeItems['admin-locale.de'];

        expect($enItem->getLabel())->toBe('Englisch');
        expect($enItem->getRoute())->toBe('fsi_admin_locale');
        expect($enItem->getRouteParameters())->toBe(array('_locale' => 'en', 'redirect_uri' => 'uri_to_redirect_to'));
        expect($enItem->getOptions())->toBe(array('attr' => array('id' => null, 'class' => null)));

        expect($deItem->getLabel())->toBe('Deutsch');
        expect($deItem->getRoute())->toBe('fsi_admin_locale');
        expect($deItem->getRouteParameters())->toBe(array('_locale' => 'de', 'redirect_uri' => 'uri_to_redirect_to'));
        expect($deItem->getOptions())->toBe(array('attr' => array('id' => null, 'class' => 'active')));
    }
}
