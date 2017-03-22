<?php

namespace FSi\FixturesBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\Form\TagType;
use Symfony\Component\Form\FormFactoryInterface;

class News extends CRUDElement
{
    public function getId()
    {
        return 'news';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('news');
        $datagrid->addColumn('title', 'text', [
            'label' => 'admin.news.list.title',
            'field_mapping' => ['title', 'subtitle'],
            'value_glue' => '<br/>',
            'editable' => true
        ]);
        $datagrid->addColumn('date', 'datetime', [
            'label' => 'admin.news.list.date',
            'datetime_format' => 'Y-m-d',
            'editable' => true,
            'form_type' => [
                'date' => TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date')
            ],
            'form_options' => [
                'date' => ['widget' => 'single_text']
            ]
        ]);
        $datagrid->addColumn('created_at', 'datetime', [
            'form_type' => ['date' => 'date'],
            'form_options' => [
                'date' => ['widget' => 'single_text']
            ]
        ]);
        $datagrid->addColumn('created_at', 'datetime', [
            'label' => 'admin.news.list.created_at'
        ]);
        $datagrid->addColumn('visible', 'boolean', [
            'label' => 'admin.news.list.visible'
        ]);
        $datagrid->addColumn('creator_email', 'text', [
            'label' => 'admin.news.list.creator_email'
        ]);
        $datagrid->addColumn('photo', 'fsi_image', [
            'label' => 'admin.news.list.photo',
            'width' => 100
        ]);
        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.news.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => "fsi_admin_crud_edit",
                    'additional_parameters' => ['element' => $this->getId()],
                    'parameters_field_mapping' => ['id' => 'id']
                ],
                'display' => [
                    'element' => DisplayNews::ID
                ]
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
                'label' => 'admin.news.list.title',
            ]
        ]);
        $datasource->addField('created_at', 'date', 'between', [
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => [
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_from',
            ],
            'form_to_options' => [
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_to',
            ]
        ]);
        $datasource->addField('visible', 'boolean', 'eq', [
            'sortable' => false,
            'form_options' => [
                'label' => 'admin.news.list.visible',
            ]
        ]);
        $datasource->addField('creator_email', 'text', 'like', [
            'field' => 'creatorEmail',
            'sortable' => true,
            'form_options' => [
                'label' => 'admin.news.list.creator_email',
            ]
        ]);

        $datasource->setMaxResults(10);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder(
            'news',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        );

        $builder->add(
            'title',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text'),
            ['label' => 'admin.news.list.title']
        );

        $builder->add(
            'date',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date'),
            [
                'label' => 'admin.news.list.date',
                'widget' => 'single_text',
                'required' => false,
            ]
        );

        $builder->add(
            'created_at',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date'),
            [
                'label' => 'admin.news.list.created_at',
                'widget' => 'single_text'
            ]
        );

        $builder->add(
            'visible',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CheckboxType', 'checkbox'),
            [
                'label' => 'admin.news.list.visible',
                'required' => false,
            ]
        );

        $builder->add(
            'creator_email',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\EmailType', 'email'),
            ['label' => 'admin.news.list.creator_email']
        );

        $builder->add(
            'photo',
            TypeSolver::getFormType('FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType', 'fsi_image'),
            ['label' => 'admin.news.list.photo']
        );

        $builder->add(
            'tags',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection'),
            [
                TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    TypeSolver::getFormType('FSi\FixturesBundle\Form\TagType', new TagType()),
                'label' => 'admin.news.list.tags',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ]
        );

        $builder->add(
            'nonEditableTags',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection'),
            [
                TypeSolver::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text'),
                'data' => new ArrayCollection(['Tag 1', 'Tag 2', 'Tag 3']),
                'label' => 'admin.news.list.non_editable_tags',
                'allow_add' => false,
                'allow_delete' => false,
                'mapped' => false,
                'required' => false
            ]
        );

        return $builder->getForm();
    }
}
