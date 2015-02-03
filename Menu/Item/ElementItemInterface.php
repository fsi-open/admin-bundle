<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

interface ElementItemInterface extends RoutableItemInterface
{
    /**
     * @return ElementInterface
     */
    public function getElement();

    /**
     * @return bool
     */
    public function hasElement();
}
