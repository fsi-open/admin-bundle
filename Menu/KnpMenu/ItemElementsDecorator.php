<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;

class ItemElementsDecorator implements ItemDecorator
{
    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem): void
    {
        if (true === $adminMenuItem instanceof ElementItem && true === $adminMenuItem->hasOption('elements')) {
            $routes = $knpMenuItem->getExtra('routes', []);

            /** @var Element[] $elements */
            $elements = $adminMenuItem->getOption('elements');
            foreach ($elements as $element) {
                $routes[] = [
                    'route' => $element->getRoute(),
                    'parameters' => $element->getRouteParameters()
                ];
            }

            $knpMenuItem->setExtra('routes', $routes);
        }
    }
}
