<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Menu\Item\Item;
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
