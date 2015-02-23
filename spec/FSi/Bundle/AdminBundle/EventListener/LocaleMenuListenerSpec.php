<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleMenuListenerSpec extends ObjectBehavior
{
    function let(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->beConstructedWith($translator, $requestStack, array('en', 'de'));
    }

    function it_should_build_locale_menu(MenuEvent $event, RequestStack $requestStack, Request $request)
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
        expect($enItem->getOptions())->toBe(array('id' => null, 'class' => null));

        expect($deItem->getLabel())->toBe('Deutsch');
        expect($deItem->getRoute())->toBe('fsi_admin_locale');
        expect($deItem->getRouteParameters())->toBe(array('_locale' => 'de', 'redirect_uri' => 'uri_to_redirect_to'));
        expect($deItem->getOptions())->toBe(array('id' => null, 'class' => 'active'));
    }
}
