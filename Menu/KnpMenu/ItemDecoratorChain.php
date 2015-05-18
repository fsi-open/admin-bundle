<?php

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Menu\Item\Item as AdminMenuItem;
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
            if (!($decorator instanceof ItemDecorator)) {
                throw new InvalidArgumentException(sprintf(
                    'Expected instance of FSi\Bundle\AdminBundle\Menu\KnpMenu\ItemDecorator but got %s',
                    is_object($decorator) ? get_class($decorator) : gettype($decorator)
                ));
            }
        }

        $this->decorators = $decorators;
    }

    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem)
    {
        foreach ($this->decorators as $decorator) {
            $decorator->decorate($knpMenuItem, $adminMenuItem);
        }
    }
}
