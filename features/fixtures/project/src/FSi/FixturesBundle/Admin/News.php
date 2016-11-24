<?php

namespace FSi\FixturesBundle\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Form\FeatureHelper;
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
        $datagrid->addColumn('title', 'text', array(
            'label' => 'admin.news.list.title',
            'field_mapping' => array('title', 'subtitle'),
            'value_glue' => '<br/>',
            'editable' => true
        ));
        $datagrid->addColumn('date', 'datetime', array(
            'label' => 'admin.news.list.date',
            'datetime_format' => 'Y-m-d',
            'editable' => true,
            'form_type' => array(
                'date' => FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date')
            ),
            'form_options' => array(
                'date' => array('widget' => 'single_text')
            )
        ));
        $datagrid->addColumn('created_at', 'datetime', array(
            'label' => 'admin.news.list.created_at'
        ));
        $datagrid->addColumn('visible', 'boolean', array(
            'label' => 'admin.news.list.visible'
        ));
        $datagrid->addColumn('creator_email', 'text', array(
            'label' => 'admin.news.list.creator_email'
        ));
        $datagrid->addColumn('photo', 'fsi_image', array(
            'label' => 'admin.news.list.photo',
            'width' => 100
        ));
        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.news.list.actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => "fsi_admin_crud_edit",
                    'additional_parameters' => array('element' => $this->getId()),
                    'parameters_field_mapping' => array('id' => 'id')
                ),
                'display' => array(
                    'element' => DisplayNews::ID
                )
            )
        ));

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', array('entity' => $this->getClassName()), 'news');
        $datasource->addField('title', 'text', 'like', array(
            'sortable' => false,
            'form_options' => array(
                'label' => 'admin.news.list.title',
            )
        ));
        $datasource->addField('created_at', 'date', 'between', array(
            'field' => 'createdAt',
            'sortable' => true,
            'form_from_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_from',
            ),
            'form_to_options' => array(
                'widget' => 'single_text',
                'label' => 'admin.news.list.created_at_to',
            )
        ));
        $datasource->addField('visible', 'boolean', 'eq', array(
            'sortable' => false,
            'form_options' => array(
                'label' => 'admin.news.list.visible',
            )
        ));
        $datasource->addField('creator_email', 'text', 'like', array(
            'field' => 'creatorEmail',
            'sortable' => true,
            'form_options' => array(
                'label' => 'admin.news.list.creator_email',
            )
        ));

        $datasource->setMaxResults(10);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $builder = $factory->createNamedBuilder(
            'news',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            array(
                'data_class' => $this->getClassName()
            )
        );

        $builder->add(
            'title',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text'),
            array(
                'label' => 'admin.news.list.title',
            )
        );

        $builder->add(
            'date',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date'),
            array(
                'label' => 'admin.news.list.date',
                'widget' => 'single_text',
                'required' => false,
            )
        );

        $builder->add(
            'created_at',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date'),
            array(
                'label' => 'admin.news.list.created_at',
                'widget' => 'single_text'
            )
        );

        $builder->add(
            'visible',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\CheckboxType', 'checkbox'),
            array(
                'label' => 'admin.news.list.visible',
                'required' => false,
            )
        );

        $builder->add(
            'creator_email',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\EmailType', 'email'),
            array(
                'label' => 'admin.news.list.creator_email'
            )
        );

        $builder->add(
            'photo',
            FeatureHelper::getFormType('FSi\Bundle\DoctrineExtensionsBundle\Form\Type\FSi\ImageType', 'fsi_image'),
            array(
                'label' => 'admin.news.list.photo'
            )
        );

        $builder->add(
            'tags',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection'),
            array(
                FeatureHelper::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    FeatureHelper::getFormType('FSi\FixturesBundle\Form\TagType', new TagType()),
                'label' => 'admin.news.list.tags',
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
            )
        );

        $builder->add(
            'nonEditableTags',
            FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\CollectionType', 'collection'),
            array(
                FeatureHelper::hasCollectionEntryTypeOption() ? 'entry_type' : 'type' =>
                    FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\TextType', 'text'),
                'data' => new ArrayCollection(['Tag 1', 'Tag 2', 'Tag 3']),
                'label' => 'admin.news.list.non_editable_tags',
                'allow_add' => false,
                'allow_delete' => false,
                'mapped' => false,
                'required' => false
            )
        );

        return $builder->getForm();
    }
}
