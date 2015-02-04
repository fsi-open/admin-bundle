<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Menu\Item\Item;

interface Builder
{
    /**
     * @return Item
     */
    public function buildMenu();
}
