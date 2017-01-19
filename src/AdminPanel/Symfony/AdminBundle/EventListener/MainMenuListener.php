<?php

namespace AdminPanel\Symfony\AdminBundle\EventListener;

use AdminPanel\Symfony\AdminBundle\Admin\ManagerInterface;
use AdminPanel\Symfony\AdminBundle\Event\MenuEvent;
use AdminPanel\Symfony\AdminBundle\Menu\Builder\Exception\InvalidYamlStructureException;
use AdminPanel\Symfony\AdminBundle\Menu\Item\ElementItem;
use AdminPanel\Symfony\AdminBundle\Menu\Item\Item;
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
     * @throws InvalidYamlStructureException
     */
    public function createMainMenu(MenuEvent $event)
    {
        $config = $this->yaml->parse(file_get_contents($this->configFilePath), true, true);

        if (!isset($config['menu'])) {
            throw new InvalidYamlStructureException(
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
                $options = array(
                    'attr' => array(
                        'class' => 'admin-element'
                    )
                );
                $options['elements'] = $this->buildItemElements($itemConfig);
                $item->setOptions($options);
            }

            if (null === $item) {
                if ($this->isSingleItem($itemConfig)) {
                    continue;
                }
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

        if (!$this->isSingleItem($itemConfig) || !$this->manager->hasElement($itemConfig['id'])) {
            return null;
        }

        return new ElementItem(
            ($this->hasEntry($itemConfig, 'name')) ? $itemConfig['name'] : $itemConfig['id'],
            $this->manager->getElement($itemConfig['id'])
        );
    }

    private function isSingleItem($itemConfig)
    {
        return $this->hasEntry($itemConfig, 'id');
    }

    private function hasEntry($itemConfig, $keyName)
    {
        return is_array($itemConfig) && array_key_exists($keyName, $itemConfig);
    }

    /**
     * @param array $itemConfig
     * @return mixed
     */
    private function buildItemElements($itemConfig)
    {
        $elements = array();

        if ($this->hasEntry($itemConfig, 'elements')) {
            $elementIds = (array)$itemConfig['elements'];
            foreach ($elementIds as $elementId) {
                $elements[] = $this->manager->getElement($elementId);
            }
        }

        return $elements;
    }
}
