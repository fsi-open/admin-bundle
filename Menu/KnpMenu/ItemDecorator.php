<?php

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
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
