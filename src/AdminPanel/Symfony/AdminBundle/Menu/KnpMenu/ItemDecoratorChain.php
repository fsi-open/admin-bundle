<?php

namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Exception\InvalidArgumentException;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as AdminMenuItem;
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
                    'Expected instance of AdminPanel\Symfony\AdminBundle\Menu\KnpMenu\ItemDecorator but got %s',
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
