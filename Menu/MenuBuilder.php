<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\Manager;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class MenuBuilder
{
    /**
     * @var \Knp\Menu\FactoryInterface
     */
    protected $factory;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Manager
     */
    protected $manager;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;


    protected $menuConfigPath;

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param string $menuConfigPath
     */
    public function __construct(FactoryInterface $factory, Manager $manager, $menuConfigPath)
    {
        $this->factory = $factory;
        $this->manager = $manager;
        $yaml = new Yaml();
        $this->menuConfigPath = $yaml->parse($menuConfigPath, true);
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
        $this->validateMenuConfiguration();

        if (isset($this->request)) {
            $menu->setCurrentUri($this->request->getRequestUri());
        }

        foreach ($this->menuConfigPath['menu'] as $elementConfig) {
            if (!is_array($elementConfig)) {
                $this->addElementToMenu($menu, $elementConfig);
                continue;
            }

            $group = key($elementConfig);
            $menu->addChild($group, array('uri' => '#'))
                ->setAttribute('dropdown', true);

            $elements = current($elementConfig);
            foreach ($elements as $groupElementConfig) {
                $this->addElementToMenu($menu[$group], $groupElementConfig);
            }
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
     * @param ItemInterface $menu
     * @param string $elementId
     * @throws \RuntimeException
     */
    protected function addElementToMenu(ItemInterface $menu, $elementId)
    {
        if (!$this->manager->hasElement($elementId)) {
            throw new \RuntimeException(sprintf("Admin manager does not contain element with id \"%s\"", $elementId));
        }
        $element = $this->manager->getElement($elementId);
        $menu->addChild($element->getName(), array(
            'route' => $element->getRoute(),
            'routeParameters' => $element->getRouteParameters(),
        ));
        $menu[$element->getName()]->setAttribute('class', 'admin-element');
    }

    /**
     * @throws \RuntimeException
     */
    private function validateMenuConfiguration()
    {
        if (!array_key_exists('menu', $this->menuConfigPath)) {
            throw new \RuntimeException("admin_menu.yml must contain \"menu\" element as a root.");
        }
    }
}
