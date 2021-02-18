<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class Subscriber extends ListElement
{
    public const ID = 'subscriber';

    public function getId(): string
    {
        return self::ID;
    }

    public function getClassName(): string
    {
        return Entity\Subscriber::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());
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
            'form_type' => [
                'email' => EmailType::class
            ]
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
            'actions' => ['edit' => ['element' => 'subscriber_form']]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        $datasource = $factory->createDataSource(
            'doctrine-orm',
            ['entity' => $this->getClassName()],
            $this->getId()
        );
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
