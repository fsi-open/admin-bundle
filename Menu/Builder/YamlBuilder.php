<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu\Builder;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
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
     * @return Item
     */
    public function buildMenu()
    {
        $menu = new Item(null);
        $config = $this->yaml->parse($this->configFilePath, true, true);
        $menuConfig = $config['menu'];

        foreach ($menuConfig as $itemConfig) {
            $menu->addChild($this->buildItem($itemConfig));
        }

        return $menu;
    }

    /**
     * @param $itemConfig
     * @return Item
     */
    private function buildItem($itemConfig)
    {
        $item = $this->buildSingleItem($itemConfig);

        if (!isset($item) && is_array($itemConfig)) {
            $item = new RoutableItem(key($itemConfig));
            $this->iterateBuildMenu($item, current($itemConfig));
        }

        return $item;
    }

    private function buildSingleItem($itemConfig)
    {
        if (is_string($itemConfig)) {
            if ($this->manager->hasElement($itemConfig)) {
                return new ElementItem($itemConfig, $this->manager->getElement($itemConfig));
            }

            return new RoutableItem($itemConfig);
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
