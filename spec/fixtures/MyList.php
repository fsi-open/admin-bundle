<?php

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class MyList extends AbstractList
{
    public function getId()
    {
    }

    public function getName()
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
