<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class ListWorker implements Worker
{
    private DataSourceFactoryInterface $dataSourceFactory;

    private DataGridFactoryInterface $dataGridFactory;

    public function __construct(
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $this->dataSourceFactory = $dataSourceFactory;
        $this->dataGridFactory = $dataGridFactory;
    }

    public function mount(Element $element): void
    {
        if (true === $element instanceof ListElement) {
            $element->setDataSourceFactory($this->dataSourceFactory);
            $element->setDataGridFactory($this->dataGridFactory);
        }
    }
}
