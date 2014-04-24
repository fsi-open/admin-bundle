<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
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
    public function mount(ElementInterface $element)
    {
        if ($element instanceof ListElement) {
            $element->setDataSourceFactory($this->dataSourceFactory);
            $element->setDataGridFactory($this->dataGridFactory);
        }
    }
}
