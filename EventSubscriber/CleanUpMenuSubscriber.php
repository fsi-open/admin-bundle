<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventSubscriber;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuMainEvent;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item as MenuItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class CleanUpMenuSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [MenuMainEvent::class => ['cleanUpMenu', -100]];
    }

    public function cleanUpMenu(MenuEvent $event): void
    {
        $this->cleanMenuLevel($event->getMenu());
    }

    private function cleanMenuLevel(MenuItem $menu): void
    {
        if (false === $menu->hasChildren()) {
            return;
        }

        foreach ($menu->getChildren() as $menuItem) {
            $this->cleanMenuLevel($menuItem);

            if (false === $menuItem instanceof RoutableItem && false === $menuItem->hasChildren()) {
                $menu->removeChild($menuItem->getName());
            }
        }
    }
}
