<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Column;

use FSi\Component\DataGrid\DataGridInterface;

interface ColumnTypeExtensionInterface
{
    /**
     * @param \FSi\Component\DataGrid\DataGridInterface $dataGrid
     * @return mixed
     *
     * @deprecated This method is deprecated since 1.2 because it is never called
     */
    public function setDataGrid(DataGridInterface $dataGrid);

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $data
     * @param mixed $object
     * @param string $index
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index);

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view);

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view);

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $value
     * @return mixed
     */
    public function filterValue(ColumnTypeInterface $column, $value);

    /**
     * Sets the default options for this type.
     *
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     */
    public function initOptions(ColumnTypeInterface $column);

    /**
     * Return array with extended column types.
     * Example return:
     *
     * return array(
     *     'text',
     *     'date_time'
     * );
     *
     * Extensions will be loaded into columns text and date_time.
     *
     * @return array
     */
    public function getExtendedColumnTypes();
}
