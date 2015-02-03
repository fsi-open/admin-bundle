<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

interface ItemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return ItemInterface[]
     */
    public function getChildren();

    /**
     * @param ItemInterface $item
     */
    public function addChild(ItemInterface $item);

    /**
     * @return bool
     */
    public function hasChildren();
}
