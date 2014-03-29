<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface;
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
