<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Menu\Item;

class RoutableItem extends Item
{
    private string $route;
    /**
     * @var array<string,mixed>
     */
    private array $routeParameters;

    /**
     * @param array<string,mixed> $routeParameters
     */
    public function __construct(string $name, string $route, array $routeParameters = [])
    {
        parent::__construct($name);

        $this->route = $route;
        $this->routeParameters = $routeParameters;
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    /**
     * @return array<string,mixed>
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }
}
