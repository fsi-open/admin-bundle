<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructure;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class MainMenuListenerSpec extends ObjectBehavior
{
    function let(Manager $manager)
    {
        $prophet = new Prophet();
        $manager->getElement(Argument::type('string'))->will(function($args) use ($prophet) {
            $element = $prophet->prophesize('FSi\Bundle\AdminBundle\Admin\Element');
            $element->getId()->willReturn($args[0]);
            return $element;
        });

        $manager->hasElement(Argument::type('string'))->willReturn(true);
        $this->beConstructedWith($manager, __DIR__ . '/admin_menu.yml');
    }

    function it_throws_exception_when_yaml_definition_of_menu_is_invalid(Manager $manager, MenuEvent $event, Item $item)
    {
        $menuYaml = __DIR__ . '/invalid_admin_menu.yml';
        $this->beConstructedWith($manager, $menuYaml);

        $this->shouldThrow(new InvalidYamlStructure(
            sprintf('File "%s" should contain top level "menu:" key', $menuYaml)
        ))->during('createMainMenu', array($event));
    }

    function it_build_menu()
    {
        $this->createMainMenu(new MenuEvent(new Item()))->shouldHaveItem('News', 'news');
        $this->createMainMenu(new MenuEvent(new Item()))->shouldHaveItem('article', 'article');
        $this->createMainMenu(new MenuEvent(new Item()))->shouldHaveItem('admin.menu.structure', false);
        $this->createMainMenu(new MenuEvent(new Item()))->shouldHaveItemThatHaveChild('admin.menu.structure', 'home_page', 'home_page');
        $this->createMainMenu(new MenuEvent(new Item()))->shouldHaveItemThatHaveChild ('admin.menu.structure', 'Contact', 'contact');
    }

    public function getMatchers()
    {
        return array(
            'haveItem' => function(Item $menu, $itemName, $elementId = false) {
                $items = $menu->getChildren();
                foreach ($items as $item) {
                    if ($item->getName() === $itemName) {
                        if (!$elementId) {
                            return true;
                        }

                        /** @var ElementItem $item */
                        return $item->getElement()->getId() === $elementId;
                    }
                }
                return false;
            },
            'haveItemThatHaveChild' => function(Item $menu, $itemName, $childName, $elementId = false) {
                foreach ($menu->getChildren() as $item) {
                    if ($item->getName() === $itemName && $item->hasChildren()) {
                        foreach ($item->getChildren() as $child) {
                            if ($child->getName() === $childName) {
                                if (!$elementId) {
                                    return true;
                                }

                                /** @var ElementItem $child */
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
