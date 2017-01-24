<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as MenuItem;

class CleanUpMenuListener
{
    public function cleanUpMenu(MenuEvent $event)
    {
        $this->cleanMenuLevel($event->getMenu());
    }

    private function cleanMenuLevel(MenuItem $menu)
    {
        if (!$menu->hasChildren()) {
            return;
        }

        foreach ($menu->getChildren() as $menuItem) {
            $this->cleanMenuLevel($menuItem);

            if (!$menuItem instanceof RoutableItem && !$menuItem->hasChildren()) {
                $menu->removeChild($menuItem->getName());
            }
        }
    }
}
