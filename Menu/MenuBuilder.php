<?php

/**
 * (c) FSi sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\Manager;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;

class MenuBuilder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    private $factory;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Manager
     */
    private $manager;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     */
    public function __construct(FactoryInterface $factory, Manager $manager)
    {
        $this->factory = $factory;
        $this->manager = $manager;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav');

        if (isset($this->request)) {
            $menu->setCurrentUri($this->request->getRequestUri());
        }

        foreach ($this->manager->getElementsWithoutGroup() as $element) {
            $menu->addChild($element->getName(), array(
                'route' => $element->getRoute(),
                'routeParameters' => $element->getRouteParameters(),
            ));
        }

        foreach ($this->manager->getGroups() as $group) {
            $menu
                ->addChild($group, array('uri' => '#'))
                ->setAttribute('dropdown', true)
            ;

            foreach ($this->manager->getElementsByGroup($group) as $element) {
                $menu[$group]->addChild($element->getName(), array(
                    'route' => $element->getRoute(),
                    'routeParameters' => $element->getRouteParameters(),
                ));
            }
        }

        return $menu;
    }
}
