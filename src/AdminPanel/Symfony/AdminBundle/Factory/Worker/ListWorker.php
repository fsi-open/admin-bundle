<?php

namespace AdminPanel\Symfony\AdminBundle\Factory\Worker;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Admin\Element;
use AdminPanel\Symfony\AdminBundle\Factory\Worker;
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
    )
    {
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
