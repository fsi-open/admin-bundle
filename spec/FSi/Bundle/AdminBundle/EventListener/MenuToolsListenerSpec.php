<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuBuilderEvent;
use FSi\Bundle\AdminBundle\Menu\Menu;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Translation\TranslatorInterface;

class MenuToolsListenerSpec extends ObjectBehavior
{
    function let(TranslatorInterface $translator)
    {
        $this->beConstructedWith(true, $translator);
    }

    function it_should_build_locale_menu(MenuBuilderEvent $event, Menu $menu)
    {
        $event->getMenu()->willReturn($menu);

        $menu->addItem(Argument::type('FSi\Bundle\AdminBundle\Menu\Item\RoutableItem'))->shouldBeCalled();

        $this->createLocaleMenu($event);
    }
}
