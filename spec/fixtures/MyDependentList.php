<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\DependentListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;

class MyDependentList extends DependentListElement
{
    public function getId(): string
    {
        return 'my_dependent_list';
    }

    public function getParentId(): string
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
    }

    public function getDataIndexer(): DataIndexerInterface
    {
    }

    public function saveDataGrid(): void
    {
    }
}
