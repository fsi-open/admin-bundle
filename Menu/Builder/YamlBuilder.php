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
        $config = $this->yaml->parse($this->configFilePath, true, true);
        $menuConfig = $config['menu'];

        $menu = new Item(null);
        $this->populateMenu($menu, $menuConfig);

        return $menu;
    }

    private function populateMenu(Item $menu, array $configs)
    {
        foreach ($configs as $itemConfig) {
            $item = $this->buildSingleItem($itemConfig);

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
}
