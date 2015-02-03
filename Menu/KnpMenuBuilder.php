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
use FSi\Bundle\AdminBundle\Menu\Item\ItemInterface;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItemInterface;
use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
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
    private $builder;

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
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @throws \RuntimeException
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->createMenuRoot();

        if (isset($this->request)) {
            $menu->setCurrentUri($this->request->getRequestUri());
        }

        foreach ($this->builder->buildMenu()->getItems() as $item) {
            if ($item->hasChildren()) {
                $menu->addChild($item->getName(), array('uri' => '#'))
                    ->setAttribute('dropdown', true);

                foreach ($item->getChildren() as $child) {
                    $this->addMenuItem($child, $menu[$item->getName()]);
                }

                continue;
            }

            $this->addMenuItem($item, $menu);
        }

        return $menu;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    protected function createMenuRoot()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');
        $menu->setChildrenAttribute('id', 'top-menu');

        return $menu;
    }

    /**
     * @param ItemInterface $item
     * @param $menu
     */
    private function addMenuItem(ItemInterface $item, MenuItem $menu)
    {
        $options = array('uri' => '#');

        if ($item instanceof RoutableItemInterface && $item->getRoute()) {
            $options = array(
                'route' => $item->getRoute(),
                'routeParameters' => $item->getRouteParameters()
            );
        }

        $menu->addChild($item->getName(), $options)->setAttribute('class', 'admin-element');
    }
}
