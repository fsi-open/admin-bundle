<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Menu\Item;

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
    public function __construct($name, $route = null, $routeParameters = [])
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
