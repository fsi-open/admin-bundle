<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CleanUpMenuListenerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\MenuEvent $event
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $menu
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\ElementItem $childItem1
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem $childItem2
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $childItem3
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem $childItem31
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $childItem4
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $childItem41
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $childItem411
     */
    public function it_remove_empty_menus(
        $event, $menu, $childItem1, $childItem2, $childItem3, $childItem31, $childItem4, $childItem41, $childItem411
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
