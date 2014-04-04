<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class News extends CRUDElement
{
    public function getId()
    {
        return 'news';
    }

    public function getName()
    {
        return 'admin.news.name';
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
            'editable' => true
        ));
        $datagrid->addColumn('date', 'datetime', array(
            'label' => 'admin.news.list.date',
            'datetime_format' => 'Y-m-d',
            'editable' => true,
            'form_type' => array('date' => 'date'),
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
        $datagrid->addColumn('actions', 'action', array(
            'label' => 'admin.news.list.actions',
            'field_mapping' => array('id'),
            'actions' => array(
                'edit' => array(
                    'route_name' => 'fsi_admin_crud_edit',
                    'additional_parameters' => array('element' => $this->getId()),
                    'parameters_field_mapping' => array('id' => 'id')
                ),
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

        $builder = $factory->createNamedBuilder('news', 'form', $data, array(
            'data_class' => $this->getClassName()
        ));

        $builder->add('title', 'text', array(
            'label' => 'admin.news.list.title',
        ));
        $builder->add('date', 'date', array(
            'label' => 'admin.news.list.date',
            'widget' => 'single_text'
        ));
        $builder->add('created_at', 'date', array(
            'label' => 'admin.news.list.created_at',
            'widget' => 'single_text'
        ));
        $builder->add('visible', 'checkbox', array(
            'label' => 'admin.news.list.visible',
        ));
        $builder->add('creator_email', 'email', array(
            'label' => 'admin.news.list.creator_email'
        ));

        return $builder->getForm();
    }
}
