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
        $item = null;
        if (is_string($itemConfig)) {
            $item = new Item($itemConfig);
            if ($this->manager->hasElement($itemConfig)) {
                $item->setElement($this->manager->getElement($itemConfig));
            }
        }
        if (is_array($itemConfig) && array_key_exists('name', $itemConfig)) {
            $item = new Item($itemConfig['name']);
        }
        if (is_array($itemConfig) && !array_key_exists('name', $itemConfig) && array_key_exists('id', $itemConfig)) {
            $item = new Item($itemConfig['id']);
        }
        if (!isset($item) && is_array($itemConfig)) {
            reset($itemConfig);
            $item = new Item(key($itemConfig));
            $this->iterateBuildMenu($item, current($itemConfig));
        }
        if (is_array($itemConfig) && array_key_exists('id', $itemConfig) && $this->manager->hasElement($itemConfig['id'])) {
            $item->setElement($this->manager->getElement($itemConfig['id']));
        }

        return $item;
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
