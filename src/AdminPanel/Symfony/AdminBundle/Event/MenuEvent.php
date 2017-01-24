<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use Symfony\Component\EventDispatcher\Event;

class MenuEvent extends Event
{
    /**
     * @var Item
     */
    private $menu;

    /**
     * @param Item $menu
     */
    public function __construct(Item $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return Item
     */
    public function getMenu()
    {
        return $this->menu;
    }
}
