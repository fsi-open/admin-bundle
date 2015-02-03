<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Menu\Item\ItemInterface;

class Menu
{
    /**
     * @var ItemInterface[]
     */
    private $items;

    public function __construct()
    {
        $this->items = array();
    }

    /**
     * @param ItemInterface $item
     */
    public function addItem(ItemInterface $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
