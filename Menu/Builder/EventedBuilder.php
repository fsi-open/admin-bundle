<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Event\MenuBuilderEvent;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventedBuilder implements Builder
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
        $menu = new Item(null);

        $this->eventDispatcher->dispatch(MenuBuilderEvent::TOOLS, new MenuBuilderEvent($menu));

        return $menu;
    }
}
