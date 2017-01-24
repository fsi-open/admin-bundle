<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormAwareInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class CRUDElement extends SimpleAdminElement implements DataGridAwareInterface, DataSourceAwareInterface, FormAwareInterface
{
    private $dataGridFactory;
    private $dataSourceFactory;
    private $formFactory;

    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->dataGridFactory = $factory;
    }

    /**
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->dataSourceFactory = $factory;
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->formFactory = $factory;
    }

    public function isFormAware()
    {
        return isset($this->formFactory);
    }

    public function isDataGridAware()
    {
        return isset($this->dataGridFactory);
    }

    public function isDataSourceAware()
    {
        return isset($this->dataSourceFactory);
    }
}
