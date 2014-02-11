<?php

namespace spec\FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use PhpSpec\ObjectBehavior;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(MenuFactory $menuFactory, Manager $manager)
    {
        $this->beConstructedWith($menuFactory, $manager);
    }

    function it_create_menu(MenuFactory $menuFactory, Manager $manager, MenuItem $menu)
    {
        $menuFactory->createItem('root')->willReturn($menu);
        $menu->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $menu->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array());
        $manager->getGroups()->willReturn(array());

        $this->createMenu()->shouldReturn($menu);
    }

    function it_create_menu_with_elements_without_group(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $menuItem,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array($element));
        $manager->getGroups()->willReturn(array());

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());
        $element->hasOption('menu')->willReturn(true);
        $element->getOption('menu')->willReturn(true);

        $root->addChild('element_name', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldBeCalled();
        $root->offsetGet('element_name')->willReturn($menuItem);
        $menuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $this->createMenu()->shouldReturn($root);
    }

    function it_create_menu_with_elements_in_groups(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $groupElement,
        MenuItem $menuItem,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array());
        $manager->getGroups()->willReturn(array('group'));

        $manager->getElementsByGroup('group')->willReturn(array($element));
        $root->addChild('group', array('uri' => '#'))->willReturn($root);
        $root->setAttribute('dropdown', true)->shouldBeCalled();
        $element->hasOption('menu')->willReturn(true);
        $element->getOption('menu')->willReturn(true);

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());

        $root->offsetGet('group')->willReturn($groupElement);
        $groupElement->addChild('element_name', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldBeCalled();
        $groupElement->offsetGet('element_name')->willReturn($menuItem);
        $menuItem->setAttribute('class', 'admin-element')->shouldBeCalled();

        $this->createMenu()->shouldReturn($root);
    }

    function it_ignore_elements_without_menu_option(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array($element));
        $manager->getGroups()->willReturn(array());

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());
        $element->hasOption('menu')->willReturn(false);
        $element->getOption('menu')->shouldNotBeCalled();

        $root->addChild('element_name', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldNotBeCalled();

        $this->createMenu()->shouldReturn($root);
    }

    function it_ignore_elements_with_menu_option_value_false(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array($element));
        $manager->getGroups()->willReturn(array());

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());
        $element->hasOption('menu')->willReturn(false);
        $element->getOption('menu')->willReturn(false);

        $root->addChild('element_name', array(
            'route' => 'fsi_admin_route',
            'routeParameters' => array(),
        ))->shouldNotBeCalled();

        $this->createMenu()->shouldReturn($root);
    }

    function it_ignore_menu_elements_in_groups_without_menu_option(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $groupElement,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array());
        $manager->getGroups()->willReturn(array('group'));

        $manager->getElementsByGroup('group')->willReturn(array($element));
        $root->addChild('group', array('uri' => '#'))->willReturn($root);
        $root->setAttribute('dropdown', true)->shouldBeCalled();
        $element->hasOption('menu')->willReturn(false);
        $element->getOption('menu')->shouldNotBeCalled();

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());

        $root->offsetGet('group')->willReturn($groupElement);

        $this->createMenu()->shouldReturn($root);
    }

    function it_ignore_menu_elements_in_groups_with_menu_option_value_false(
        MenuFactory $menuFactory,
        Manager $manager,
        MenuItem $root,
        MenuItem $groupElement,
        ElementInterface $element
    ) {
        $menuFactory->createItem('root')->willReturn($root);
        $root->setChildrenAttribute('class', 'nav navbar-nav')->shouldBeCalled();
        $root->setChildrenAttribute('id', 'top-menu')->shouldBeCalled();

        $manager->getElementsWithoutGroup()->willReturn(array());
        $manager->getGroups()->willReturn(array('group'));

        $manager->getElementsByGroup('group')->willReturn(array($element));
        $root->addChild('group', array('uri' => '#'))->willReturn($root);
        $root->setAttribute('dropdown', true)->shouldBeCalled();
        $element->hasOption('menu')->willReturn(false);
        $element->getOption('menu')->willReturn(false);

        $element->getName()->willReturn('element_name');
        $element->getRoute()->willReturn('fsi_admin_route');
        $element->getRouteParameters()->willReturn(array());

        $root->offsetGet('group')->willReturn($groupElement);

        $this->createMenu()->shouldReturn($root);
    }
}
