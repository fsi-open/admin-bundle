<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Menu\Builder\Builder;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\KnpMenu\ItemDecorator;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;

class MenuBuilderSpec extends ObjectBehavior
{
    public function let(FactoryInterface $factory, ItemDecorator $itemDecorator): void
    {
        $this->beConstructedWith($factory, [$itemDecorator]);
    }

    public function it_builds_knp_menu_and_decorates_items(
        FactoryInterface $factory,
        ItemInterface $knpRootItem,
        ItemInterface $knpFirstItem,
        ItemInterface $knpSecondItem,
        ItemInterface $knpChildOfSecondItem,
        ItemDecorator $itemDecorator,
        Builder $builder,
        Item $rootItem,
        Item $firstItem,
        Item $secondItem,
        Item $childOfSecondItem
    ): void {
        $builder->buildMenu()->willReturn($rootItem);
        $firstItem->getName()->willReturn('first item');
        $firstItem->hasChildren()->willReturn(false);
        $secondItem->getName()->willReturn('second item');
        $secondItem->hasChildren()->willReturn(true);
        $childOfSecondItem->getName()->willReturn('child of second item');
        $childOfSecondItem->hasChildren()->willReturn(false);
        $rootItem->getChildren()->willReturn([$firstItem, $secondItem]);
        $secondItem->getChildren()->willReturn([$childOfSecondItem]);
        $rootItem->getOption('attr')->willReturn(['id' => null, 'class' => 'some class']);

        $factory->createItem('root')->willReturn($knpRootItem);
        $knpRootItem->addChild('first item', [])->willReturn($knpFirstItem);
        $knpRootItem->addChild('second item', [])->willReturn($knpSecondItem);
        $knpSecondItem->addChild('child of second item', [])->willReturn($knpChildOfSecondItem);

        $knpRootItem->setChildrenAttribute('id', null)->shouldBeCalled();
        $knpRootItem->setChildrenAttribute('class', 'some class')->shouldBeCalled();
        $itemDecorator->decorate($knpFirstItem, $firstItem)->shouldBeCalled();
        $itemDecorator->decorate($knpSecondItem, $secondItem)->shouldBeCalled();
        $itemDecorator->decorate($knpChildOfSecondItem, $childOfSecondItem)->shouldBeCalled();

        $this->createMenu($builder);
    }
}
