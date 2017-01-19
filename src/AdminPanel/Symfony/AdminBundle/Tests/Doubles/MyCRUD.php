<?php


namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD;
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
        $datasource = $factory->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:MyEntity'), 'my_datasource');

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form');

        return $form;
    }
}
