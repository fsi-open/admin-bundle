<?php


namespace AdminPanel\Symfony\AdminBundle\Menu\Builder;

use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class MenuBuilder implements Builder
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var string
     */
    private $eventName;

    public function __construct(EventDispatcherInterface $eventDispatcher, $eventName)
    {
        $this->eventName = $eventName;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return Item
     */
    public function buildMenu()
    {
        $menu = new Item();

        $this->eventDispatcher->dispatch($this->eventName, new MenuEvent($menu));

        return $menu;
    }
}
