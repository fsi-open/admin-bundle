<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\FixturesBundle\Entity;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Person extends CRUDElement
{
    public function getId(): string
    {
        return 'person';
    }

    public function getClassName(): string
    {
        return Entity\Person::class;
    }

    public function createDataGrid(): DataGridInterface
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

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

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
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
                    'route_name' => 'fsi_admin_form',
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ]
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

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $builder = $factory->createNamedBuilder(
            'person',
            TypeSolver::getFormType(FormType::class, 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'email',
            TypeSolver::getFormType(TextType::class, 'text'),
            ['label' => 'admin.email']
        );

        return $builder->getForm();
    }
}
