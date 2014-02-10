<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class CustomNews extends CRUDElement
{
    public function getId()
    {
        return 'custom_news';
    }

    public function getName()
    {
        return 'admin.news.custom_name';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('news');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array('entity' => $this->getClassName()), 'news');

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {

        $builder = $factory->createNamedBuilder('news', 'form', $data, array(
            'data_class' => $this->getClassName()
        ));

        return $builder->getForm();
    }
}
