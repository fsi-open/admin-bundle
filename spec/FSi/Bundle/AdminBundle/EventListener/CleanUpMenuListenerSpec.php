<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FSi\Bundle\AdminBundle\Menu\Item\Item as MenuItem;

class CleanUpMenuListenerSpec extends ObjectBehavior
{
    public function it_remove_empty_menus(
        MenuEvent $event,
        MenuItem $menu,
        ElementItem $childItem1,
        RoutableItem $childItem2,
        MenuItem $childItem3,
        RoutableItem $childItem31,
        MenuItem $childItem4,
        MenuItem $childItem41,
        MenuItem $childItem411
    ) {
        $event->getMenu()->willReturn($menu);

        $menu->hasChildren()->willReturn(true);

        $childItem3->getName()->willReturn('item3');
        $childItem3->hasChildren()->willReturn(true);
        $childItem3->getChildren()->willReturn([$childItem31]);

        $childItem4->getName()->willReturn('item4');
        $childItem4->hasChildren()->willReturn(false); //after cleanup of item41
        $childItem4->getChildren()->willReturn([$childItem41]);
        $menu->removeChild('item4')->shouldBeCalled();

        $childItem41->getName()->willReturn('item41');
        $childItem41->hasChildren()->willReturn(false); //after cleanup of item411
        $childItem41->getChildren()->willReturn([$childItem411]);

        $childItem411->getName()->willReturn('item411');
        $childItem411->hasChildren()->willReturn(false);

        $menu->getChildren()->willReturn([
            $childItem1,
            $childItem2,
            $childItem3,
            $childItem4
        ]);

        $this->cleanUpMenu($event);
    }
}
