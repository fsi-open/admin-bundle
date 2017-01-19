<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use PhpSpec\ObjectBehavior;

class MenuBuilderSpec extends ObjectBehavior
{
    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \AdminPanel\Symfony\AdminBundle\Menu\KnpMenu\ItemDecorator $itemDecorator
     */
    function let($factory, $itemDecorator)
    {
        $this->beConstructedWith($factory, $itemDecorator);
    }

    /**
     * @param \Knp\Menu\FactoryInterface $factory
     * @param \Knp\Menu\ItemInterface $knpRootItem
     * @param \Knp\Menu\ItemInterface $knpFirstItem
     * @param \Knp\Menu\ItemInterface $knpSecondItem
     * @param \Knp\Menu\ItemInterface $knpChildOfSecondItem
     * @param \AdminPanel\Symfony\AdminBundle\Menu\KnpMenu\ItemDecorator $itemDecorator
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Builder\Builder $builder
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $rootItem
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $firstItem
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $secondItem
     * @param \AdminPanel\Symfony\AdminBundle\Menu\Item\Item $childOfSecondItem
     */
    function it_builds_knp_menu_and_decorates_items(
        $factory,
        $knpRootItem,
        $knpFirstItem,
        $knpSecondItem,
        $knpChildOfSecondItem,
        $itemDecorator,
        $builder,
        $rootItem,
        $firstItem,
        $secondItem,
        $childOfSecondItem
    )
    {
        $builder->buildMenu()->willReturn($rootItem);
        $firstItem->getName()->willReturn('first item');
        $firstItem->hasChildren()->willReturn(false);
        $secondItem->getName()->willReturn('second item');
        $secondItem->hasChildren()->willReturn(true);
        $childOfSecondItem->getName()->willReturn('child of second item');
        $childOfSecondItem->hasChildren()->willReturn(false);
        $rootItem->getChildren()->willReturn(array($firstItem, $secondItem));
        $secondItem->getChildren()->willReturn(array($childOfSecondItem));
        $rootItem->getOption('attr')->willReturn(array('id' => null, 'class' => 'some class'));

        $factory->createItem('root')->willReturn($knpRootItem);
        $knpRootItem->addChild('first item', array())->willReturn($knpFirstItem);
        $knpRootItem->addChild('second item', array())->willReturn($knpSecondItem);
        $knpSecondItem->addChild('child of second item', array())->willReturn($knpChildOfSecondItem);

        $knpRootItem->setChildrenAttribute('id', null)->shouldBeCalled();
        $knpRootItem->setChildrenAttribute('class', 'some class')->shouldBeCalled();
        $itemDecorator->decorate($knpFirstItem, $firstItem)->shouldBeCalled();
        $itemDecorator->decorate($knpSecondItem, $secondItem)->shouldBeCalled();
        $itemDecorator->decorate($knpChildOfSecondItem, $childOfSecondItem)->shouldBeCalled();

        $this->createMenu($builder);
    }
}
