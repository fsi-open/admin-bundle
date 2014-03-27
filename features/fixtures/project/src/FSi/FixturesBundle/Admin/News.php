<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class News extends ListElement
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
        return 'FSi\FixturesBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('news');
        $datagrid->addColumn('title', 'text', array(
            'label' => 'admin.news.list.title',
            'editable' => true
        ));
        $datagrid->addColumn('date', 'datetime', array(
            'label' => 'admin.news.list.date',
            'datetime_format' => 'Y-m-d',
            'editable' => true,
            'form_type' => array('date' => 'date'),
            'form_options' => array(
                'date' => array('widget' => 'single_text')
            )
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
        $datasource->addField('title', 'text', 'like', array(
            'sortable' => false,
            'form_options' => array(
                'label' => 'admin.news.list.title',
            )
        ));
        $datasource->addField('created_at', 'date', 'between', array(
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_from',
            ),
            'form_to_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_to',
            )
        ));
        $datasource->addField('visible', 'boolean', 'eq', array(
            'sortable' => false,
            'form_options' => array(
                'label' => 'admin.news.list.visible',
            )
        ));
        $datasource->addField('creator_email', 'text', 'like', array(
            'field' => 'creatorEmail',
            'sortable' => true,
            'form_options' => array(
                'label' => 'admin.news.list.creator_email',
            )
        ));

        $datasource->setMaxResults(10);

        return $datasource;
    }
}
