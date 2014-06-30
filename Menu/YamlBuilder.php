<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use Symfony\Component\Yaml\Yaml;

class YamlBuilder implements Builder
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
     * @param \FSi\Bundle\AdminBundle\Admin\ManagerInterface $manager
     * @param string $configFilePath
     */
    public function __construct(ManagerInterface $manager, $configFilePath)
    {
        $this->configFilePath = $configFilePath;
        $this->yaml = new Yaml();
        $this->manager = $manager;
    }

    /**
     * @return Menu
     */
    public function buildMenu()
    {
        $menu = new Menu();
        $config = $this->yaml->parse($this->configFilePath, true, true);
        $menuConfig = $config['menu'];

        foreach ($menuConfig as $itemConfig) {
            $item = $this->buildItem($itemConfig);

            $menu->addItem($item);
        }

        return $menu;
    }

    /**
     * @param $itemConfig
     * @return array
     */
    private function buildItem($itemConfig)
    {
        $item = $this->buildSingleItem($itemConfig);

        if (!isset($item) && is_array($itemConfig)) {
            $item = new Item(key($itemConfig));
            $this->iterateBuildMenu($item, current($itemConfig));
        }

        if ($this->hasEntry($itemConfig, 'id') && $this->manager->hasElement($itemConfig['id'])) {
            $item->setElement($this->manager->getElement($itemConfig['id']));
        }

        return $item;
    }

    private function buildSingleItem($itemConfig)
    {
        if (is_string($itemConfig)) {
            $item = new Item($itemConfig);
            if ($this->manager->hasElement($itemConfig)) {
                $item->setElement($this->manager->getElement($itemConfig));
            }
            return $item;
        }

        if ($this->hasEntry($itemConfig, 'name')) {
            return new Item($itemConfig['name']);
        }

        if ($this->hasEntry($itemConfig, 'id')) {
            return new Item($itemConfig['id']);
        }
    }

    private function hasEntry($itemConfig, $keyName)
    {
        return is_array($itemConfig) && array_key_exists($keyName, $itemConfig);
    }

    /**
     * @param Item $item
     * @param array $config
     */
    private function iterateBuildMenu(Item $item, array $config)
    {
        foreach ($config as $itemConfig) {
            $child =  $this->buildItem($itemConfig);
            $item->addChild($child);
        }
    }
}
