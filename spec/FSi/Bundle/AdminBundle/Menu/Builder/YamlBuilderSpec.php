<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Admin\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class YamlBuilderSpec extends ObjectBehavior
{
    function let(Manager $manager)
    {
        $prophet = new Prophet();
        $manager->getElement(Argument::type('string'))->will(function($args) use ($prophet) {
            $element = $prophet->prophesize('FSi\Bundle\AdminBundle\Admin\ElementInterface');
            $element->getId()->willReturn($args[0]);
            return $element;
        });

        $manager->hasElement(Argument::type('string'))->willReturn(true);
        $this->beConstructedWith($manager, __DIR__ . '/admin_menu.yml');
    }

    function it_build_menu()
    {
        $this->buildMenu()->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Menu\Menu');
        $this->buildMenu()->shouldHaveItem('News', 'news');
        $this->buildMenu()->shouldHaveItem('article', 'article');
        $this->buildMenu()->shouldHaveItem('admin.menu.structure', false);
        $this->buildMenu()->shouldHaveItemThatHaveChild('admin.menu.structure', 'home_page', 'home_page');
        $this->buildMenu()->shouldHaveItemThatHaveChild ('admin.menu.structure', 'Contact', 'contact');
    }

    public function getMatchers()
    {
        return array(
            'haveItem' => function($menu, $itemName, $elementId = false) {
                    $items = $menu->getItems();
                    foreach ($items as $item) {
                        if ($item->getName() === $itemName) {
                            if (!$elementId) {
                                return true;
                            }

                            return $item->getElement()->getId() === $elementId;
                        }
                    }
                    return false;
                },
            'haveItemThatHaveChild' => function($menu, $itemName, $childName, $elementId = false) {
                    foreach ($menu->getItems() as $item) {
                        if ($item->getName() === $itemName && $item->hasChildren()) {
                            foreach ($item->getChildren() as $child) {
                                if ($child->getName() === $childName) {
                                    if (!$elementId) {
                                        return true;
                                    }

                                    return $child->getElement()->getId() === $elementId;
                                }
                            }
                        }
                    }
                    return false;
                }
        );
    }
}
