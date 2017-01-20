<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class DependencyInjectionExtension extends DataGridAbstractExtension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $columnServiceIds;

    /**
     * @var array
     */
    protected $columnExtensionServiceIds;

    /**
     * @var array
     */
    protected $gridSubscriberServiceIds;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $columnServiceIds
     * @param array $columnExtensionServiceIds
     * @param array $gridSubscriberServiceIds
     * @internal param array $columnExtensionsServiceIds
     */
    public function __construct(
        ContainerInterface $container,
        array $columnServiceIds,
        array $columnExtensionServiceIds,
        array $gridSubscriberServiceIds
    ) {
        $this->container = $container;
        $this->columnServiceIds = $columnServiceIds;
        $this->columnExtensionServiceIds = $columnExtensionServiceIds;
        $this->gridSubscriberServiceIds = $gridSubscriberServiceIds;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnTypeExtensions($type)
    {
        foreach ($this->columnExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->columnExtensionServiceIds[$alias]);
            $types = $extension->getExtendedColumnTypes();
            if (in_array($type, $types)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function hasColumnType($type)
    {
        return isset($this->columnServiceIds[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType($type)
    {
        if (!isset($this->columnServiceIds[$type])) {
            throw new \InvalidArgumentException(sprintf('The column type "%s" is not registered with the service container.', $type));
        }

        $type = $this->container->get($this->columnServiceIds[$type]);

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnTypeExtensions($type)
    {
        $columnExtension = array();

        foreach ($this->columnExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->columnExtensionServiceIds[$alias]);
            $types = $extension->getExtendedColumnTypes();
            if (in_array($type, $types)) {
                $columnExtension[] = $extension;
            }
        }

        return $columnExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        $subscribers = array();

        foreach ($this->gridSubscriberServiceIds as $alias => $subscriberName) {
            $subscriber = $this->container->get($this->gridSubscriberServiceIds[$alias]);
            $subscribers[] = $subscriber;
        }

        return $subscribers;
    }
}
