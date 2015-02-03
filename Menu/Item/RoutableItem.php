<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

class RoutableItem implements RoutableItemInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var RoutableItem[]
     */
    private $children;

    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParameters;

    /**
     * @param string|null $name
     * @param $route
     * @param $routeParameters
     */
    public function __construct($name, $route = null, $routeParameters = array())
    {
        $this->children = array();
        $this->name = $name;
        $this->route = $route;
        $this->routeParameters = $routeParameters;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param ItemInterface $item
     */
    public function addChild(ItemInterface $item)
    {
        $this->children[] = $item;
    }

    /**
     * @return int
     */
    public function hasChildren()
    {
        return (boolean) count($this->children);
    }

    /**
     * @return ItemInterface[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @return array
     */
    public function getRouteParameters()
    {
        return $this->routeParameters;
    }
}
