<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\DataGrid\NewsDataGridBuilder;
use FSi\FixturesBundle\Form\NewsType;
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

        NewsDataGridBuilder::buildNewsDataGrid($datagrid);

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
        return $factory->createNamed('news', new NewsType(), $data);
    }
}
