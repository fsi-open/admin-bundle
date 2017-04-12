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
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('category');
        $datagrid->addColumn('title', 'text', [
            'label' => 'admin.category.list.title',
            'field_mapping' => ['title'],
            'editable' => true
        ]);
        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.category.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'news' => [
                    'route_name' => "fsi_admin_crud_list",
                    'additional_parameters' => ['element' => 'category_news'],
                    'parameters_field_mapping' => ['parent' => 'id'],
                    'redirect_uri' => false,
                ],
                'edit' => [
                    'route_name' => "fsi_admin_crud_edit",
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
            ]
        ]);

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', ['entity' => $this->getClassName()], 'news');
        $datasource->addField('title', 'text', 'like', [
            'sortable' => false,
            'form_options' => [
                'label' => 'admin.category.list.title',
            ]
        ]);

        $datasource->setMaxResults(10);

        return $datasource;
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
