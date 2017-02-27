<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MyCRUD extends AbstractCRUD
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getDataIndexer()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function saveDataGrid()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid('my_datagrid');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', ['entity' => 'FSiDemoBundle:MyEntity'], 'my_datasource');

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form');

        return $form;
    }
}
