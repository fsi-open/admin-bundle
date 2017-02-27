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
        $datagrid->addColumn('batch', 'batch', [
            'actions' => [
                [
                    'element' => 'subscriber_delete',
                    'label' => 'crud.list.batch.delete'
                ]
            ]
        ]);
        $datagrid->addColumn('email', 'text', [
            'label' => 'admin.subscriber.list.email',
            'editable' => true,
            'form_type' => ['email' => 'email']
        ]);
        $datagrid->addColumn('active', 'boolean', [
            'label' => 'admin.subscriber.list.active'
        ]);
        $datagrid->addColumn('created_at', 'datetime', [
            'label' => 'admin.subscriber.list.created_at'
        ]);
        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.subscriber.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'element' => 'subscriber_form'
                ]
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', ['entity' => $this->getClassName()], 'subscriber');
        $datasource->addField('email', 'text', 'like', [
            'sortable' => true,
            'form_options' => [
                'label' => 'admin.subscriber.list.email',
            ]
        ]);
        $datasource->addField('created_at', 'date', 'between', [
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => [
                'widget' => 'single_text',
                'label' => 'admin.subscriber.list.created_at_from',
            ],
            'form_to_options' => [
                'widget' => 'single_text',
                'label' => 'admin.subscriber.list.created_at_to',
            ]
        ]);
        $datasource->addField('active', 'boolean', 'eq', [
            'sortable' => false,
            'form_options' => [
                'label' => 'admin.subscriber.list.active',
            ]
        ]);

        $datasource->setMaxResults(10);

        return $datasource;
    }
}
