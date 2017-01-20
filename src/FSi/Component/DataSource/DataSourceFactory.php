<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource;

use FSi\Component\DataSource\Driver\DriverFactoryManagerInterface;
use FSi\Component\DataSource\Exception\DataSourceException;

/**
 * {@inheritdoc}
 */
class DataSourceFactory implements DataSourceFactoryInterface
{
    /**
     * Array of registered names for data sources.
     *
     * @var array
     */
    protected $datasources;

    /**
     * @var \FSi\Component\DataSource\Driver\DriverFactoryManagerInterface
     */
    protected $driverFactoryManager;

    /**
     * Array of factory extensions.
     *
     * @var array
     */
    protected $extensions = array();

    /**
     * @param \FSi\Component\DataSource\Driver\DriverFactoryManagerInterface $driverFactoryManager
     * @param array $extensions
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function __construct(DriverFactoryManagerInterface $driverFactoryManager, $extensions = array())
    {
        $this->driverFactoryManager = $driverFactoryManager;

        foreach ($extensions as $extension) {
            if (!$extension instanceof DataSourceExtensionInterface) {
                throw new DataSourceException(sprintf('Instance of DataSourceExtensionInterface expected, "%s" given.', is_object($extension) ? get_class($extension) : gettype($extension)));
            }
        }

        $this->extensions = $extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataSource($driver, $driverOptions = array(), $name = 'datasource')
    {
        $name = (string) $name;

        if (!$this->driverFactoryManager->hasFactory($driver)) {
            throw new DataSourceException(sprintf('Driver "%s" doesn\'t exist.', $driver));
        }

        $driverFactory = $this->driverFactoryManager->getFactory($driver);
        $driver = $driverFactory->createDriver($driverOptions);

        $this->checkDataSourceName($name);

        $datasource = new DataSource($driver, $name);
        $this->datasources[$name] = $datasource;

        foreach ($this->extensions as $extension) {
            $datasource->addExtension($extension);
        }

        $datasource->setFactory($this);

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function addExtension(DataSourceExtensionInterface $extension)
    {
        $this->extensions[] = $extension;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtensions()
    {
        return $this->extensions;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllParameters()
    {
        $result = array();
        foreach ($this->datasources as $datasource) {
            $result[] = $datasource->getParameters();
        }

        if ($result) {
            return call_user_func_array('array_merge', $result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function getOtherParameters(DataSourceInterface $except)
    {
        $result = array();
        foreach ($this->datasources as $datasource) {
            if ($datasource !== $except) {
                $result[] = $datasource->getParameters();
            }
        }

        if ($result) {
            return call_user_func_array('array_merge_recursive', $result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function addDataSource(DataSourceInterface $datasource)
    {
        $name = $datasource->getName();
        $this->checkDataSourceName($name, $datasource);
        $this->datasources[$name] = $datasource;
        $datasource->setFactory($this);
    }

    /**
     * Method to checking datasources name.
     *
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     * @param string $name
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     *
     */
    private function checkDataSourceName($name, DataSourceInterface $datasource = null)
    {
        if (empty($name)) {
            throw new DataSourceException('Name of data source can\'t be empty.');
        }

        if (isset($this->datasources[$name]) && ($this->datasources[$name] !== $datasource)) {
            throw new DataSourceException('Name of data source must be unique.');
        }

        if (!preg_match('/^[\w\d]+$/', $name)) {
            throw new DataSourceException('Name of data source may contain only word characters and digits.');
        }
    }
}
