<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver;

use FSi\Component\DataSource\DataSourceInterface;

/**
 * Driver is responsible for fetching data based on passed fields and data.
 */
interface DriverInterface
{
    /**
     * Returns type (name) of this driver.
     *
     * @return string
     */
    public function getType();

    /**
     * Sets reference to DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     */
    public function setDataSource(DataSourceInterface $datasource);

    /**
     * Return reference to assigned DataSource.
     *
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    public function getDataSource();

    /**
     * Checks if driver has field for given type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldType($type);

    /**
     * Return field for given type.
     *
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $type
     */
    public function getFieldType($type);

    /**
     * Returns collection with result.
     *
     * Returned object must implement interfaces Countable and IteratorAggregate. Count on this object must return amount
     * of all available results.
     *
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable, \IteratorAggregate
     */
    public function getResult($fields, $first, $max);

    /**
     * Returns loaded extensions.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Adds extension to driver.
     *
     * @param \FSi\Component\DataSource\Driver\DriverExtensionInterface $extension
     */
    public function addExtension(DriverExtensionInterface $extension);
}
