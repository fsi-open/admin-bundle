<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

interface ListElement
{
    /**
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    public function createDataSource();

    /**
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    public function createDataGrid();

    /**
     * @param DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory);

    /**
     * @param DataGridFactoryInterface $factory
     * @return mixed
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory);
}