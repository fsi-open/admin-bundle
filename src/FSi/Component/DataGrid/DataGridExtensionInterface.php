<?php

declare(strict_types=1);

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
