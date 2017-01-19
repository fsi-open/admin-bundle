<?php

namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;

class ItemLabelDecorator implements ItemDecorator
{
    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem)
    {
        if ($adminMenuItem->getLabel()) {
            $knpMenuItem->setLabel($adminMenuItem->getLabel());
        }
    }
}
