<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class Subscriber extends ListElement
{
    public function getId()
    {
        return 'subscriber';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Subscriber';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('subscriber');
        $datagrid->addColumn('batch', 'batch', array(
            'actions' => array(
                array(
                    'element' => 'subscriber_delete',
                    'label' => 'crud.list.batch.delete'
                )
            )
        ));
        $datagrid->addColumn('email', 'text', array(
            'label' => 'admin.subscriber.list.email',
            'editable' => true
        ));
        $datagrid->addColumn('active', 'boolean', array(
            'label' => 'admin.subscriber.list.active'
        ));
        $datagrid->addColumn('created_at', 'datetime', array(
            'label' => 'admin.subscriber.list.created_at'
        ));
        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.subscriber.list.actions',
            'field_mapping' => array('id'),
            'admin_edit_element_id' => 'subscriber_form'
        ));

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array('entity' => $this->getClassName()), 'subscriber');
        $datasource->addField('email', 'text', 'like', array(
            'sortable' => true,
            'form_options' => array(
                'label' => 'admin.subscriber.list.email',
            )
        ));
        $datasource->addField('created_at', 'date', 'between', array(
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.subscriber.list.created_at_from',
            ),
            'form_to_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.subscriber.list.created_at_to',
            )
        ));
        $datasource->addField('active', 'boolean', 'eq', array(
            'sortable' => false,
            'form_options' => array(
                'label' => 'admin.subscriber.list.active',
            )
        ));

        $datasource->setMaxResults(10);

        return $datasource;
    }
}
