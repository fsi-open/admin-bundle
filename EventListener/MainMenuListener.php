<?php

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructure;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use Symfony\Component\Yaml\Yaml;

class MainMenuListener
{
    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @var Yaml
     */
    private $yaml;

    /**
     * @var ManagerInterface
     */
    private $manager;

    /**
     * @param ManagerInterface $manager
     * @param string $configFilePath
     */
    public function __construct(ManagerInterface $manager, $configFilePath)
    {
        $this->configFilePath = $configFilePath;
        $this->yaml = new Yaml();
        $this->manager = $manager;
    }

    /**
     * @param MenuEvent $event
     * @return Item
     * @throws InvalidYamlStructure
     */
    public function createMainMenu(MenuEvent $event)
    {
        $config = $this->yaml->parse($this->configFilePath, true, true);

        if (!isset($config['menu'])) {
            throw new InvalidYamlStructure(
                sprintf('File "%s" should contain top level "menu:" key', $this->configFilePath)
            );
        }

        $menu = $event->getMenu();
        $menu->setOptions(array(
            'attr' => array(
                'id' => 'top-menu',
                'class' => 'nav navbar-nav',
            )
        ));

        $this->populateMenu($menu, $config['menu']);

        return $menu;
    }

    private function populateMenu(Item $menu, array $configs)
    {
        foreach ($configs as $itemConfig) {
            $item = $this->buildSingleItem($itemConfig);

            if (null !== $item) {
                $item->setOptions(array('attr' => array('class' => 'admin-element',)));
            }

            if (null === $item && is_array($itemConfig)) {
                $item = new Item(key($itemConfig));
                $group = array_values($itemConfig);
                $this->populateMenu($item, $group[0]);
            }

            $menu->addChild($item);
        }
    }

    private function buildSingleItem($itemConfig)
    {
        if (is_string($itemConfig)) {
            if ($this->manager->hasElement($itemConfig)) {
                return new ElementItem($itemConfig, $this->manager->getElement($itemConfig));
            }

            return new Item($itemConfig);
        }

        if (!$this->hasEntry($itemConfig, 'id')) {
            return null;
        }

        return new ElementItem(
            ($this->hasEntry($itemConfig, 'name')) ? $itemConfig['name'] : $itemConfig['id'],
            $this->manager->getElement($itemConfig['id'])
        );
    }

    private function hasEntry($itemConfig, $keyName)
    {
        return is_array($itemConfig) && array_key_exists($keyName, $itemConfig);
    }
}
