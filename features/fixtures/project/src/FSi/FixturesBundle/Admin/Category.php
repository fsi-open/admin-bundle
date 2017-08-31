<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class Category extends CRUDElement
{
    public function getId()
    {
        return 'category';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Category';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
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
                    'route_name' => "fsi_admin_list",
                    'additional_parameters' => ['element' => 'category_news'],
                    'parameters_field_mapping' => ['parent' => 'id'],
                    'redirect_uri' => false,
                ],
                'edit' => [
                    'route_name' => "fsi_admin_form",
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
            ]
        ]);
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        return $factory->createDataSource(
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

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder(
            'category',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'title',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text'),
            ['label' => 'admin.category.list.title']
        );

        return $builder->getForm();
    }
}
