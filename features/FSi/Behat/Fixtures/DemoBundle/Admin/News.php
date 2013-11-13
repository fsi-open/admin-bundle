<?php

namespace FSi\Behat\Fixtures\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class News extends CRUDElement
{
    public function getId()
    {
        return 'news';
    }

    public function getName()
    {
        return 'admin.news.name';
    }

    public function getClassName()
    {
        return 'FSi\Behat\Fixtures\DemoBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('news');

        $datagrid->addColumn('title', 'text', array(
            'label' => 'admin.news.list.title',
            'editable' => true
        ));

        $datagrid->addColumn('created_at', 'datetime', array(
            'label' => 'admin.news.list.created_at'
        ));

        $datagrid->addColumn('visible', 'boolean', array(
            'label' => 'admin.news.list.visible'
        ));

        $datagrid->addColumn('creator_email', 'text', array(
            'label' => 'admin.news.list.creator_email'
        ));

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array('entity' => $this->getClassName()), 'news');

        $datasource->addField('title', 'text', 'like');
        $datasource->addField('created_at', 'date', 'between');
        $datasource->addField('visible', 'boolean', 'eq');
        $datasource->addField('creator_email', 'text', 'like');

        $datasource->setMaxResults(10);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
    }
}
