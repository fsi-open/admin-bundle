<?php

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;

class ItemAttributesDecorator implements ItemDecorator
{
    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem)
    {
        $knpMenuItem->setUri('#');

        if ($adminMenuItem->hasOption('attr')) {
            $knpMenuItem->setAttributes($adminMenuItem->getOption('attr'));
        }

        if ($adminMenuItem->hasChildren()) {
            $knpMenuItem->setAttribute('dropdown', true);
        }
    }
}
