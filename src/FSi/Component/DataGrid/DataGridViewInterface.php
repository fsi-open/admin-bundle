<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Data\DataRowsetInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

interface DataGridViewInterface extends \Iterator, \Countable, \ArrayAccess
{
    /**
     * Returns datagrid name.
     *
     * @return string
     */
    public function getName();

    /**
     * Check if column is registered in view.
     *
     * @param string $name
     * @return boolean
     */
    public function hasColumn($name);

    /**
     * Checks if column with specific type was added to grid.
     *
     * @param string $type
     * @return boolean
     */
    public function hasColumnType($type);

    /**
     * Removes column from view.
     *
     * @param string $name
     */
    public function removeColumn($name);

    /**
     * Get column.
     *
     * @throws \InvalidArgumentException
     * @param string $name
     */
    public function getColumn($name);

    /**
     * Return all columns registered in view.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Remove all columns from view.
     */
    public function clearColumns();

    /**
     * Add new column to view.
     *
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $column
     */
    public function addColumn(HeaderViewInterface $column);

    /**
     * Set new column list set to view.
     *
     * @param array $columns
     */
    public function setColumns(array $columns);
}
