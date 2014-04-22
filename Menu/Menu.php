<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

class Menu
{
    /**
     * @var Item[]
     */
    private $items;

    public function __construct()
    {
        $this->items = array();
    }

    /**
     * @param Item $item
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
