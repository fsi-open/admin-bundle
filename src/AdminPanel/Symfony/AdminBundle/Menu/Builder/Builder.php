<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Menu\Builder;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;

interface Builder
{
    /**
     * @return Item
     */
    public function buildMenu();
}
