<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\DependencyInjection;

use FSi\Component\DataSource\DataSourceAbstractExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * DependencyInjection extension loads various types of extensions from Symfony's service container.
 */
class DependencyInjectionExtension extends DataSourceAbstractExtension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var array
     */
    protected $driverExtensionServiceIds;

    /**
     * @var array
     */
    protected $subscriberServiceIds;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param array $driverExtensionServiceIds
     * @param array $subscriberServiceIds
     */
    public function __construct(ContainerInterface $container, $driverExtensionServiceIds, $subscriberServiceIds)
    {
        $this->container = $container;
        $this->driverExtensionServiceIds = $driverExtensionServiceIds;
        $this->subscriberServiceIds = $subscriberServiceIds;
    }

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        $extensions = array();

        foreach ($this->driverExtensionServiceIds as $alias => $extensionName) {
            $extension = $this->container->get($this->driverExtensionServiceIds[$alias]);
            $extensions[] = $extension;
        }

        return $extensions;
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
