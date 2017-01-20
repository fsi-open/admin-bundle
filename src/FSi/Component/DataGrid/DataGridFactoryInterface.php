<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

interface DataGridFactoryInterface
{
    /**
     * Check if column is registered in factory. Column types can be registered
     * only by extensions.
     *
     * @param string $type
     * @return boolean
     */
    public function hasColumnType($type);

    /**
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException if column is not registered in factory.
     * @param string $type
     * @return \FSi\Component\DataGrid\Column\ColumnTypeInterface
     */
    public function getColumnType($type);

    /**
     * Return all registered in factory DataGrid extensions as array.
     *
     * @return array
     */
    public function getExtensions();

    /**
     * Create data grid with unique name.
     *
     * @param string $name
     * @return \FSi\Component\DataGrid\DataGridInterface
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    public function createDataGrid($name = 'grid');

    /**
     * @return \FSi\Component\DataGrid\DataMapper\DataMapperInterface
     */
    public function getDataMapper();

    /**
     * @deprecated This method is deprecated and it will removed in version 1.2
     * @return \FSi\Component\DataGrid\Data\IndexingStrategyInterface
     */
    public function getIndexingStrategy();
}
