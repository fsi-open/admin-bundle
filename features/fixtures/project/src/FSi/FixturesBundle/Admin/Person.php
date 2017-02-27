<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use RuntimeException;
use Symfony\Component\Form\FormFactoryInterface;

class Person extends CRUDElement
{
    public function getId()
    {
        return 'person';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Person';
    }

    public function createDataGrid()
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
            throw new RuntimeException('initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface');
        }

        if (!$datagrid->hasColumnType('batch')) {
            $datagrid->addColumn('batch', 'batch', [
                'actions' => [
                    'delete' => [
                        'route_name' => 'fsi_admin_batch',
                        'additional_parameters' => ['element' => $this->getId()],
                        'label' => 'crud.list.batch.delete'
                    ]
                ],
                'display_order' => -1000
            ]);
        }

        return $datagrid;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid($this->getId());

        $datagrid->addColumn('email', 'text', [
            'label' => 'admin.email'
        ]);

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.news.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => "fsi_admin_crud_edit",
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ]
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        return $factory->createDataSource(
            'doctrine',
            ['entity' => $this->getClassName()],
            $this->getId()
        );
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder('person', 'form', $data, [
            'data_class' => $this->getClassName()
        ]);

        $builder->add('email', 'text', ['label' => 'admin.email']);

        return $builder->getForm();
    }
}
