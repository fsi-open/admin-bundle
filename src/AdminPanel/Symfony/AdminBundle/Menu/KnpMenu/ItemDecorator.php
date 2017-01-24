<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;

interface ItemDecorator
{
    /**
     * @param KnpMenuItem $knpMenuItem
     * @param AdminMenuItem $adminMenuItem
     * @return mixed
     */
    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem);
}
