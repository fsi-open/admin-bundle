<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

interface ListElement extends DataIndexerElement, DataSourceAwareInterface, DataGridAwareInterface
{
    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function createDataGrid();

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function createDataSource();

    /**
     * Method called after DataGrid update at listAction in CRUDController.
     * Mostly it should only call flush at ObjectManager.
     *
     * @return mixed
     */
    public function saveDataGrid();
}
