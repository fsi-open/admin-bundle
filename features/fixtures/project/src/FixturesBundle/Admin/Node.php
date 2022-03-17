<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity;

class Node extends DependentListElement
{
    public function getId(): string
    {
        return 'node';
    }

    public function getParentId(): string
    {
        return 'node';
    }

    public function getClassName(): string
    {
        return Entity\Node::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('title', 'text', [
            'label' => 'admin.node.list.title',
        ]);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.node.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'nodes' => [
                    'route_name' => 'fsi_admin_list',
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['parent' => 'id']
                ],
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        return $factory->createDataSource(
            'doctrine-orm',
            ['entity' => $this->getClassName()],
            $this->getId()
        );
    }
}
