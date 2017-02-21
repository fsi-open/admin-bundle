<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructureException;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophet;

class MainMenuListenerSpec extends ObjectBehavior
{
    function let(ManagerInterface $manager)
    {
        $prophet = new Prophet();
        $manager->getElement(Argument::type('string'))->will(function($args) use ($prophet) {
            if ($args[0] == 'non_existing') {
                throw new \Exception(sprintf('Element %s does not exist', $args[0]));
            };
            $element = $prophet->prophesize('FSi\Bundle\AdminBundle\Admin\Element');
            $element->getId()->willReturn($args[0]);
            return $element;
        });

        $manager->hasElement(Argument::type('string'))->will(function ($args) {
            return $args[0] != 'non_existing';
        });
        $this->beConstructedWith($manager, __DIR__ . '/admin_menu.yml');
    }

    function it_throws_exception_when_yaml_definition_of_menu_is_invalid(
        ManagerInterface $manager,
        MenuEvent $event
    ) {
        $menuYaml = __DIR__ . '/invalid_admin_menu.yml';
        $this->beConstructedWith($manager, $menuYaml);

        $this->shouldThrow(new InvalidYamlStructureException(
            sprintf('File "%s" should contain top level "menu:" key', $menuYaml)
        ))->during('createMainMenu', array($event));
    }

    function it_build_menu()
    {
        $menu = $this->createMainMenu(new MenuEvent(new Item()));

        $menu->shouldHaveItem('News', 'news');
        $menu->shouldHaveItem('article', 'article');
        $menu->shouldHaveItem('admin.menu.structure', false);
        $menu->shouldHaveItemThatHaveChild('admin.menu.structure', 'home_page', 'home_page');
        $menu->shouldHaveItemThatHaveChild ('admin.menu.structure', 'Contact', 'contact');
        $menu->shouldHaveItemThatHaveChild ('admin.menu.structure', 'Offer', 'offer');

        $offerItem = $menu->getChildren()['admin.menu.structure']->getChildren()['Offer'];
        $offerItem->getOption('elements')[0]->getId()->shouldReturn('category');
        $offerItem->getOption('elements')[1]->getId()->shouldReturn('product');
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
