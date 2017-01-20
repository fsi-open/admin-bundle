<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource;

/**
 * Factory maintains building new DataSources with preconfigured extensions.
 */
interface DataSourceFactoryInterface
{
    /**
     * Creates instance of data source with given driver and name.
     *
     * @param string $driver
     * @param array $driverOptions
     * @param $name
     * @return \FSi\Component\DataSource\DataSource
     */
    public function createDataSource($driver, $driverOptions = array(), $name);

    /**
     * Adds extension to list.
     *
     * @param \FSi\Component\DataSource\DataSourceExtensionInterface $extension
     */
    public function addExtension(DataSourceExtensionInterface $extension);

    /**
     * Return array of loaded extensions.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Return array of all parameters from all datasources.
     *
     * @return array
     */
    public function getAllParameters();

    /**
     * Return array of all parameters form all datasources except given.
     *
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     * @return array
     */
    public function getOtherParameters(DataSourceInterface $datasource);

    /**
     * Adds given datasource to list of known datasources, so its data will be fetched
     * during getAllParameters and getOtherParameters.
     *
     * Factory also automatically sets its (datasource) factory to itself.
     *
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     */
    public function addDataSource(DataSourceInterface $datasource);
}
