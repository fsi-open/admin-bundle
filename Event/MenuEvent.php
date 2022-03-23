<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Symfony\Contracts\EventDispatcher\Event;

class MenuEvent extends Event
{
    private Item $menu;

    public function __construct(Item $menu)
    {
        $this->menu = $menu;
    }

    public function getMenu(): Item
    {
        return $this->menu;
    }
}
