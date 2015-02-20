<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Translation\TranslatorInterface;

class LocaleMenuListenerSpec extends ObjectBehavior
{
    function let(TranslatorInterface $translator)
    {
        $this->beConstructedWith(true, $translator);
    }

    function it_should_build_locale_menu(MenuEvent $event, Item $menu)
    {
        $event->getMenu()->willReturn($menu);

        $menu->addChild(Argument::type('FSi\Bundle\AdminBundle\Menu\Item\Item'))->shouldBeCalled();

        $this->createLocaleMenu($event);
    }
}
