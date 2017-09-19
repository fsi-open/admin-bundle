<?php

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

class Category extends CRUDElement
{
    public function getId(): string
    {
        return 'category';
    }

    public function getClassName(): string
    {
        return Entity\Category::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        return $factory->createDataGrid($this->getId())
            ->addColumn('title', 'text', [
                'label' => 'admin.category.list.title',
                'field_mapping' => ['title'],
                'editable' => true
            ])->addColumn('actions', 'action', [
                'label' => 'admin.category.list.actions',
                'field_mapping' => ['id'],
                'actions' => [
                    'news' => [
                        'route_name' => 'fsi_admin_list',
                        'additional_parameters' => ['element' => 'category_news'],
                        'parameters_field_mapping' => ['parent' => 'id'],
                        'redirect_uri' => false,
                    ],
                    'edit' => [
                        'route_name' => 'fsi_admin_form',
                        'additional_parameters' => ['element' => $this->getId()],
                        'parameters_field_mapping' => ['id' => 'id']
                    ],
                ]
            ]);
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        return $factory
            ->createDataSource(
                'doctrine-orm',
                ['entity' => $this->getClassName()],
                'news'
            )->addField('title', 'text', 'like', [
                'sortable' => false,
                'form_options' => [
                    'label' => 'admin.category.list.title',
                ]
            ])->setMaxResults(10);
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        $builder = $factory->createNamedBuilder(
            'category',
            TypeSolver::getFormType(FormType::class, 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'title',
            TypeSolver::getFormType(TextType::class, 'text'),
            ['label' => 'admin.category.list.title']
        );

        return $builder->getForm();
    }
}
