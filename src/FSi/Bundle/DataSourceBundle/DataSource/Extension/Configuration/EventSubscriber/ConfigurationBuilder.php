<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\DataSource\Extension\Configuration\EventSubscriber;

use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Event\DataSourceEvent;
use FSi\Component\DataSource\Event\DataSourceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Parser;

class ConfigurationBuilder implements EventSubscriberInterface
{
    /**
     * @var \Symfony\Component\HttpKernel\KernelInterface
     */
    protected $kernel;

    /**
     * @param KernelInterface $kernel
     */
    function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(DataSourceEvents::PRE_BIND_PARAMETERS => array('readConfiguration', 1024));
    }

    /**
     * Method called at PreBindParameters event.
     *
     * @param \FSi\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs $event
     */
    public function readConfiguration(DataSourceEvent\ParametersEventArgs $event)
    {
        $dataSource = $event->getDataSource();
        $dataSourceConfiguration = array();
        foreach ($this->kernel->getBundles() as $bundle) {
            if ($this->hasDataSourceConfiguration($bundle->getPath(), $dataSource->getName())) {
                $configuration = $this->getDataSourceConfiguration($bundle->getPath(), $dataSource->getName());

                if (is_array($configuration)) {
                    $dataSourceConfiguration = $configuration;
                }
            }
        }

        if (count($dataSourceConfiguration)) {
            $this->buildConfiguration($dataSource, $dataSourceConfiguration);
        }
    }

    /**
     * @param string $bundlePath
     * @param string $dataSourceName
     * @return bool
     */
    protected function hasDataSourceConfiguration($bundlePath, $dataSourceName)
    {
        return file_exists(sprintf($bundlePath . '/Resources/config/datasource/%s.yml', $dataSourceName));
    }

    /**
     * @param string $bundlePath
     * @param string $dataSourceName
     * @return mixed
     */
    protected function getDataSourceConfiguration($bundlePath, $dataSourceName)
    {
        $yamlParser = new Parser();
        return $yamlParser->parse(file_get_contents(sprintf($bundlePath . '/Resources/config/datasource/%s.yml', $dataSourceName)));
    }

    /**
     * @param DataSourceInterface $dataSource
     * @param array $configuration
     */
    protected function buildConfiguration(DataSourceInterface $dataSource, array $configuration)
    {
        foreach ($configuration['fields'] as $name => $field) {
            $type = array_key_exists('type', $field)
                ? $field['type']
                : null;
            $comparison = array_key_exists('comparison', $field)
                ? $field['comparison']
                : null;
            $options = array_key_exists('options', $field)
                ? $field['options']
                : array();

            $dataSource->addField($name, $type, $comparison, $options);
        }
    }
}
