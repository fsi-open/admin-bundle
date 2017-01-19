<?php

namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as AdminMenuItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\RoutableItem;
use Knp\Menu\ItemInterface as KnpMenuItem;
use Symfony\Component\Routing\RouterInterface;

class ItemRouteDecorator implements ItemDecorator
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function decorate(KnpMenuItem $knpMenuItem, AdminMenuItem $adminMenuItem)
    {
        if ($adminMenuItem instanceof RoutableItem && $adminMenuItem->getRoute()) {
            $knpMenuItem->setUri($this->router->generate(
                $adminMenuItem->getRoute(),
                $adminMenuItem->getRouteParameters()
            ));

            $routes = $knpMenuItem->getExtra('routes', array());
            $routes[] = array(
                'route' => $adminMenuItem->getRoute(),
                'parameters' => $adminMenuItem->getRouteParameters()
            );
            $knpMenuItem->setExtra('routes', $routes);
        }
    }
}
