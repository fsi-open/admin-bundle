<?php

namespace spec\FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(
        MenuFactory $menuFactory,
        MenuItem $menu,
        Manager $manager,
        ElementInterface $newsElement,
        ElementInterface $homePageElement
    ) {
        $manager->hasElement('news')->willReturn(true);
        $manager->getElement('news')->willReturn($newsElement);
        $manager->hasElement('home_page')->willReturn(true);
        $manager->getElement('home_page')->willReturn($homePageElement);

        $newsElement->getName()->willReturn('News');
        $newsElement->getRoute()->willReturn('fsi_admin_route');
        $newsElement->getRouteParameters()->willReturn(array());
        $homePageElement->getName()->willReturn('Home Page');
        $homePageElement->getRoute()->willReturn('fsi_admin_route');
        $homePageElement->getRouteParameters()->willReturn(array());

        $menuFactory->createItem('root')->willReturn($menu);
        $menu->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $menu->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();
    }

    function it_create_menu(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $menu,
        MenuItem $menuItem,
        MenuItem $subMenu,
        MenuItem $subMenuItem
    ) {
        $this->beConstructedWith($menuFactory, $manager, __DIR__ . '/admin_menu.yml');

        $menu->addChild('News', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldBeCalled();
        $menu->offsetGet('News')->willReturn($menuItem);
        $menuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $menu->addChild('admin.menu.structure', array('uri' => '#'))->willReturn($subMenu);
        $subMenu->setAttribute('dropdown', true)->shouldBeCalled();

        $menu->offsetGet('admin.menu.structure')->willReturn($subMenu);
        $subMenu->addChild('Home Page', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldBeCalled();
        $subMenu->offsetGet('Home Page')->willReturn($subMenuItem);
        $subMenuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $this->createMenu()->shouldReturn($menu);
    }

    function it_throw_exception_when_element_is_not_present_in_manager(MenuFactory $menuFactory, Manager $manager)
    {
        $manager->hasElement('products')->willReturn(false);
        $this->beConstructedWith($menuFactory, $manager, __DIR__ . '/admin_menu_with_fake_elements.yml');
        $this->shouldThrow(new \RuntimeException("Admin manager does not contain element with id \"products\""))->during('createMenu', array());
    }


    function it_throw_exception_menu_configuration_does_not_have_valid_root(MenuFactory $menuFactory, Manager $manager)
    {
        $this->beConstructedWith($menuFactory, $manager, __DIR__ . '/invalid_admin_menu.yml');
        $this->shouldThrow(new \RuntimeException("admin_menu.yml must contain \"menu\" element as a root."))->during('createMenu', array());
    }
}
