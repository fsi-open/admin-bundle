<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

interface DataGridExtensionInterface
{
    /**
     * Register event subscribers.
     *
     * @param \FSi\Component\DataGrid\DataGridInterface\DataGridInterface $dataGrid
     */
    public function registerSubscribers(DataGridInterface $dataGrid);

    /**
     * Check if extension has column type of $type.
     *
     * @param string $type
     */
    public function hasColumnType($type);

    /**
     * Get column type.
     *
     * @param string $type
     */
    public function getColumnType($type);

    /**
     * Check if extension has any column type extension for column of $type.
     *
     * @param string $type
     */
    public function hasColumnTypeExtensions($type);

    /**
     * Return extensions for column type provided by this data grid extension.
     *
     * @param string $type
     */
    public function getColumnTypeExtensions($type);
}
