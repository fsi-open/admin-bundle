<?php

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;

class DataGridAwareElement extends SimpleAdminElement implements DataGridAwareInterface
{
    private $dataGridFactory;

    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->dataGridFactory = $factory;
    }
}
