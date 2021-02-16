<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Menu\Builder\Builder;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface as KnpItemInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ItemDecorator
     */
    protected $itemDecorator;

    public function __construct(FactoryInterface $factory, ItemDecorator $itemDecorator)
    {
        $this->factory = $factory;
        $this->itemDecorator = $itemDecorator;
    }

    public function createMenu(Builder $builder): KnpItemInterface
    {
        $rootMenuItem = $builder->buildMenu();
        $knpMenuItem = $this->createMenuRoot($rootMenuItem);

        $this->populateMenu($knpMenuItem, $rootMenuItem->getChildren());

        return $knpMenuItem;
    }

    private function createMenuRoot(Item $rootMenuItem): KnpItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', $rootMenuItem->getOption('attr')['class']);
        $menu->setChildrenAttribute('id', $rootMenuItem->getOption('attr')['id']);

        return $menu;
    }

    /**
     * @param KnpItemInterface $menu
     * @param array<Item> $children
     */
    private function populateMenu(KnpItemInterface $menu, array $children): void
    {
        foreach ($children as $item) {
            $knpItem = $menu->addChild($item->getName(), []);

            if (true === $item->hasChildren()) {
                $this->populateMenu($knpItem, $item->getChildren());
            }

            $this->itemDecorator->decorate($knpItem, $item);
        }
    }
}
