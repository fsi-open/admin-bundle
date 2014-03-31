<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class DataSourceWorker implements Worker
{
    /**
     * @var DataSourceFactoryInterface
     */
    private $dataSourceFactory;

    /**
     * @param DataSourceFactoryInterface $dataSourceFactory
     */
    function __construct(DataSourceFactoryInterface $dataSourceFactory)
    {
        $this->dataSourceFactory = $dataSourceFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(ElementInterface $element)
    {
        if ($element instanceof DataSourceAwareInterface) {
            $element->setDataSourceFactory($this->dataSourceFactory);
        }
    }
}
