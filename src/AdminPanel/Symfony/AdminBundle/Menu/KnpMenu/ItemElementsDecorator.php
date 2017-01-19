<?php

namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Menu\Item\ElementItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item as AdminMenuItem;
use Knp\Menu\ItemInterface as KnpMenuItem;
use Symfony\Component\Routing\RouterInterface;

class ItemElementsDecorator implements ItemDecorator
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
        if ($adminMenuItem instanceof ElementItem && $adminMenuItem->hasOption('elements')) {
            $routes = $knpMenuItem->getExtra('routes', array());

            /** @var Element $element */
            foreach ($adminMenuItem->getOption('elements') as $element) {
                $routes[] = array(
                    'route' => $element->getRoute(),
                    'parameters' => $element->getRouteParameters()
                );
            }

            $knpMenuItem->setExtra('routes', $routes);
        }
    }
}
