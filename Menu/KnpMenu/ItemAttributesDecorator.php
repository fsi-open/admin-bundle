<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;

class ItemAttributesDecorator implements ItemDecorator
{
    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem): void
    {
        $knpMenuItem->setUri('#');

        if (true === $adminMenuItem->hasOption('attr')) {
            $knpMenuItem->setAttributes($adminMenuItem->getOption('attr'));
        }

        if (true === $adminMenuItem->hasChildren()) {
            $knpMenuItem->setAttribute('dropdown', true);
        }
    }
}
