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
use FSi\Bundle\AdminBundle\Menu\Item\Item as MenuItem;

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
