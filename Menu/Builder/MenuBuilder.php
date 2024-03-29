<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Psr\EventDispatcher\EventDispatcherInterface;

class MenuBuilder implements Builder
{
    private EventDispatcherInterface $eventDispatcher;
    private string $eventName;

    /**
     * @param class-string<MenuEvent> $eventName
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, string $eventName)
    {
        $this->eventName = $eventName;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function buildMenu(): Item
    {
        $menu = new Item();

        $this->eventDispatcher->dispatch(new $this->eventName($menu));

        return $menu;
    }
}
