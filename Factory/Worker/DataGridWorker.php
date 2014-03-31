<?php

namespace FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Factory\Worker;
use FSi\Component\DataGrid\DataGridFactoryInterface;

class DataGridWorker implements Worker
{
    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    private $dataGridFactory;

    /**
     * @param DataGridFactoryInterface $dataGridFactory
     */
    function __construct(DataGridFactoryInterface $dataGridFactory)
    {
        $this->dataGridFactory = $dataGridFactory;
    }

    /**
     * @inheritdoc
     */
    public function mount(ElementInterface $element)
    {
        if ($element instanceof DataGridAwareInterface) {
            $element->setDataGridFactory($this->dataGridFactory);
        }
    }
}
