<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuEvents;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ToolsMenuBuilder implements Builder
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return Item
     */
    public function buildMenu()
    {
        $menu = new Item();

        $this->eventDispatcher->dispatch(MenuEvents::TOOLS, new MenuEvent($menu));

        return $menu;
    }
}
