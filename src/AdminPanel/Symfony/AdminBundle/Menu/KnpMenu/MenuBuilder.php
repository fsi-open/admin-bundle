<?php


namespace AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Menu\Builder\Builder;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface as KnpItemInterface;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    protected $factory;

    /**
     * @var ItemDecorator
     */
    protected $itemDecorator;

    /**
     * @param FactoryInterface $factory
     * @param ItemDecorator $itemDecorator
     */
    public function __construct(FactoryInterface $factory, ItemDecorator $itemDecorator)
    {
        $this->factory = $factory;
        $this->itemDecorator = $itemDecorator;
    }

    /**
     * @param Builder $builder
     * @return \Knp\Menu\ItemInterface
     */
    public function createMenu(Builder $builder)
    {
        $rootMenuItem = $builder->buildMenu();
        $knpMenuItem = $this->createMenuRoot($rootMenuItem);

        $this->populateMenu($knpMenuItem, $rootMenuItem->getChildren());

        return $knpMenuItem;
    }

    /**
     * @param Item $rootMenuItem
     * @return KnpItemInterface
     */
    protected function createMenuRoot(Item $rootMenuItem)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', $rootMenuItem->getOption('attr')['class']);
        $menu->setChildrenAttribute('id', $rootMenuItem->getOption('attr')['id']);

        return $menu;
    }

    /**
     * @param \Knp\Menu\ItemInterface $menu
     * @param Item[] $children
     */
    protected function populateMenu(KnpItemInterface $menu, array $children)
    {
        foreach ($children as $item) {
            $knpItem = $menu->addChild($item->getName(), array());

            if ($item->hasChildren()) {
                $this->populateMenu($knpItem, $item->getChildren());
            }

            $this->itemDecorator->decorate($knpItem, $item);
        }
    }
}
