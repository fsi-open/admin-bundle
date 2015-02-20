<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Item;

class RoutableItem extends Item
{
    /**
     * @var string
     */
    private $route;

    /**
     * @var array
     */
    private $routeParameters;

    /**
     * @param string $name
     * @param $route
     * @param $routeParameters
     */
    public function __construct($name, $route = null, $routeParameters = array())
    {
        parent::__construct($name);

        $this->route = $route;
        $this->routeParameters = $routeParameters;
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
