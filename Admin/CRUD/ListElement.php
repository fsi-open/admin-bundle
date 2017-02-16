<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

interface ListElement extends DataIndexerElement
{
    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataGrid();

    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory);

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataSource();

    /**
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory);

    /**
     * Method called after DataGrid update at listAction in CRUDController.
     * Mostly it should only call flush at ObjectManager.
     *
     * @return mixed
     */
    public function saveDataGrid();
}
