<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\EventListener;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\MenuEvent;
use FSi\Bundle\AdminBundle\Event\MenuMainEvent;
use FSi\Bundle\AdminBundle\Menu\Builder\Exception\InvalidYamlStructureException;
use FSi\Bundle\AdminBundle\Menu\Item\ElementItem;
use FSi\Bundle\AdminBundle\Menu\Item\Item;
use FSi\Bundle\AdminBundle\Menu\Item\RoutableItem;
use RuntimeException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

use function array_key_exists;
use function is_array;
use function is_string;

class MainMenuListener implements EventSubscriberInterface
{
    private string $configFilePath;

    private ManagerInterface $manager;

    public static function getSubscribedEvents(): array
    {
        return [
            MenuMainEvent::class => 'createMainMenu',
        ];
    }

    public function __construct(ManagerInterface $manager, string $configFilePath)
    {
        $this->configFilePath = $configFilePath;
        $this->manager = $manager;
    }

    public function createMainMenu(MenuEvent $event): Item
    {
        $yamlContent = file_get_contents($this->configFilePath);
        if (false === is_string($yamlContent)) {
            throw new RuntimeException("Unable to read contents of {$this->configFilePath}");
        }
        $config = Yaml::parse($yamlContent, Yaml::PARSE_OBJECT | Yaml::PARSE_EXCEPTION_ON_INVALID_TYPE);

        if (false === array_key_exists('menu', $config)) {
            throw new InvalidYamlStructureException(
                sprintf('File "%s" should contain top level "menu:" key', $this->configFilePath)
            );
        }

        $menu = $event->getMenu();
        $menu->setOptions([
            'attr' => [
                'id' => 'top-menu',
                'class' => 'nav navbar-nav',
            ]
        ]);

        $this->populateMenu($menu, $config['menu']);

        return $menu;
    }

    /**
     * @param Item $menu
     * @param array<string,mixed> $configs
     */
    private function populateMenu(Item $menu, array $configs): void
    {
        foreach ($configs as $itemConfig) {
            $item = $this->buildSingleItem($itemConfig);

            if (null !== $item) {
                $options = ['attr' => ['class' => 'admin-element']];
                if (true === $item instanceof ElementItem) {
                    $options['elements'] = $this->buildItemElements($itemConfig);
                }
                $item->setOptions($options);
            }

            if (null === $item) {
                if (true === $this->isSingleItem($itemConfig)) {
                    continue;
                }
                $item = new Item(key($itemConfig));
                $group = array_values($itemConfig);
                $this->populateMenu($item, $group[0]);
            }

            $menu->addChild($item);
        }
    }

    /**
     * @param array<string,mixed>|string $itemConfig
     * @return Item|null
     */
    private function buildSingleItem($itemConfig): ?Item
    {
        if (true === is_string($itemConfig)) {
            if (true === $this->manager->hasElement($itemConfig)) {
                return new ElementItem($itemConfig, $this->manager->getElement($itemConfig));
            }

            return new Item($itemConfig);
        }

        if (false === $this->isSingleItem($itemConfig)) {
            return null;
        }

        $id = $this->getEntry($itemConfig, 'id');
        if (true === is_string($id) && true === $this->manager->hasElement($id)) {
            $name = $this->getEntry($itemConfig, 'name');
            return new ElementItem((true === is_string($name)) ? $name : $id, $this->manager->getElement($id));
        }

        if (true === $this->hasEntry($itemConfig, 'route')) {
            return new RoutableItem(
                $itemConfig['name'] ?? $itemConfig['route'],
                $itemConfig['route'],
                $itemConfig['route_parameters'] ?? []
            );
        }

        return null;
    }

    /**
     * @param array<string,mixed>|string $itemConfig
     * @return bool
     */
    private function isSingleItem($itemConfig): bool
    {
        return true === $this->hasEntry($itemConfig, 'id') || true === $this->hasEntry($itemConfig, 'route');
    }

    /**
     * @param array<string,mixed>|string $itemConfig
     * @param string $keyName
     * @return bool
     */
    private function hasEntry($itemConfig, string $keyName): bool
    {
        return true === is_array($itemConfig) && true === array_key_exists($keyName, $itemConfig);
    }

    /**
     * @param array<string,mixed>|string$itemConfig
     * @param string $keyName
     * @return array<string,mixed>|string|null
     */
    private function getEntry($itemConfig, string $keyName)
    {
        return (true === is_array($itemConfig) && true === array_key_exists($keyName, $itemConfig))
            ? $itemConfig[$keyName]
            : null;
    }

    /**
     * @param array<string,mixed>|string $itemConfig
     * @return array<int,Element>
     */
    private function buildItemElements($itemConfig): array
    {
        $elements = [];

        $elementIds = $this->getEntry($itemConfig, 'elements');
        if (null !== $elementIds) {
            $elementIds = (array) $elementIds;
            foreach ($elementIds as $elementId) {
                $elements[] = $this->manager->getElement($elementId);
            }
        }

        return $elements;
    }
}
