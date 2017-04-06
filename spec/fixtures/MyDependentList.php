<?php

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\DependentListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class MyDependentList extends DependentListElement
{
    public function getId()
    {
    }

    public function getParentId()
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
    }

    public function getDataIndexer()
    {
    }

    public function saveDataGrid()
    {
    }
}
