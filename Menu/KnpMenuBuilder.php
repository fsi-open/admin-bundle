<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Menu\Builder\Builder;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface as KnpItemInterface;
use Symfony\Component\HttpFoundation\Request;

class KnpMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Builder $builder
     * @param FactoryInterface $factory
     */
    public function __construct(Builder $builder, FactoryInterface $factory)
    {
        $this->factory = $factory;
        $this->builder = $builder;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @throws \RuntimeException
     * @return KnpItemInterface
     */
    public function createMenu()
    {
        $rootMenuItem = $this->builder->buildMenu();
        $knpMenuItem = $this->createMenuRoot($rootMenuItem);

        $this->populateMenu($knpMenuItem, $rootMenuItem->getChildren());

        return $knpMenuItem;
    }

    /**
     * @param Item $rootMenuItem
     * @return KnpItemInterface
     */
    protected function createMenuRoot(Item $rootMenuItem)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', $rootMenuItem->getOption('class'));
        $menu->setChildrenAttribute('id', $rootMenuItem->getOption('id'));

        return $menu;
    }

    protected function populateMenu(KnpItemInterface $menu, array $children)
    {
        foreach ($children as $item) {
            /** @var $item Item */
            $knpItem = $this->addMenuItem($menu, $item);

            if ($item->hasChildren()) {
                $knpItem->setAttribute('dropdown', true);
                $this->populateMenu($knpItem, $item->getChildren());
            }
        }
    }

    /**
     * @param KnpItemInterface $menu
     * @param Item $item
     * @return KnpItemInterface $menu
     */
    protected function addMenuItem(KnpItemInterface $menu, Item $item)
    {
        $options = array(
            'uri' => '#',
            'attributes' => $item->getOptions(),
        );

        if ($item instanceof RoutableItem && $item->getRoute()) {
            $options = array(
                'route' => $item->getRoute(),
                'routeParameters' => $item->getRouteParameters(),
                'attributes' => $item->getOptions(),
            );
        }

        $child = $menu->addChild($item->getName(), $options);

        if ($item->getLabel()) {
            $child->setLabel($item->getLabel());
        }

        return $child;
    }
}
