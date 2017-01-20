<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\DependencyInjection\Driver;

use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DependencyInjection extension loads various types of extensions from Symfony's service container.
 */
class DriverExtension extends DriverAbstractExtension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var string
     */
    protected $driverType;

    /**
     * @var array
     */
    protected $fieldServiceIds;

    /**
     * @var array
     */
    protected $fieldExtensionServiceIds;

    /**
     * @var array
     */
    protected $subscriberServiceIds;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param string $driverType
     * @param array $fieldServiceIds
     * @param array $fieldExtensionServiceIds
     * @param array $subscriberServiceIds
     */
    public function __construct(ContainerInterface $container, $driverType, array $fieldServiceIds, array $fieldExtensionServiceIds, array $subscriberServiceIds)
    {
        $this->container = $container;
        $this->driverType = $driverType;
        $this->fieldServiceIds = $fieldServiceIds;
        $this->fieldExtensionServiceIds = $fieldExtensionServiceIds;
        $this->subscriberServiceIds = $subscriberServiceIds;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array($this->driverType);
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldType($type)
    {
        return isset($this->fieldServiceIds[$type]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldType($type)
    {
        if (!isset($this->fieldServiceIds[$type])) {
            throw new \InvalidArgumentException(sprintf('The field type "%s" is not registered within the service container.', $type));
        }

        $type = $this->container->get($this->fieldServiceIds[$type]);

        return $type;
    }

    /**
     * {@inheritdoc}
     */
    public function hasFieldTypeExtensions($type)
    {
        foreach ($this->fieldExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->fieldExtensionServiceIds[$alias]);
            $types = $extension->getExtendedFieldTypes();
            if (in_array($type, $types)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getFieldTypeExtensions($type)
    {
        $fieldExtension = array();

        foreach ($this->fieldExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->fieldExtensionServiceIds[$alias]);
            $types = $extension->getExtendedFieldTypes();
            if (in_array($type, $types)) {
                $fieldExtension[] = $extension;
            }
        }

        return $fieldExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        $subscribers = array();

        foreach ($this->subscriberServiceIds as $alias => $subscriberName) {
            $subscriber = $this->container->get($this->subscriberServiceIds[$alias]);
            $subscribers[] = $subscriber;
        }

        return $subscribers;
    }
}
