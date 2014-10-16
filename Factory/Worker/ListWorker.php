<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class ListWorker implements Worker
{
    /**
     * @var DataSourceFactoryInterface
     */
    private $dataSourceFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    private $dataGridFactory;

    /**
     * @param DataSourceFactoryInterface $dataSourceFactory
     */
    function __construct(
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $this->dataSourceFactory = $dataSourceFactory;
        $this->dataGridFactory = $dataGridFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(Element $element)
    {
        if ($element instanceof ListElement) {
            $element->setDataSourceFactory($this->dataSourceFactory);
            $element->setDataGridFactory($this->dataGridFactory);
            return;
        }
        if ($element instanceof DataSourceAwareInterface) {
            $element->setDataSourceFactory($this->dataSourceFactory);
        }
        if ($element instanceof DataGridAwareInterface) {
            $element->setDataGridFactory($this->dataGridFactory);
        }
    }
}
