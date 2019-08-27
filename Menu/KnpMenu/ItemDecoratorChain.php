<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
use FSi\Bundle\AdminBundle\Menu\KnpMenu\ItemDecorator;
use Knp\Menu\ItemInterface as KnpMenuItem;

class ItemDecoratorChain implements ItemDecorator
{
    /**
     * @var ItemDecorator[]
     */
    private $decorators;

    public function __construct(array $decorators)
    {
        foreach ($decorators as $decorator) {
            if (false === $decorator instanceof ItemDecorator) {
                throw new InvalidArgumentException(sprintf(
                    'Expected instance of "%s" but got "%s"',
                    ItemDecorator::class,
                    is_object($decorator) ? get_class($decorator) : gettype($decorator)
                ));
            }
        }

        $this->decorators = $decorators;
    }

    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem): void
    {
        foreach ($this->decorators as $decorator) {
            $decorator->decorate($knpMenuItem, $adminMenuItem);
        }
    }
}
