<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\FixturesBundle\DataGrid\NewsDataGridBuilder;
use FSi\FixturesBundle\DataSource\NewsDataSourceBuilder;
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

        $datagrid->addColumn('actions', 'action', [
            'label' => 'admin.news.list.actions',
            'field_mapping' => ['id'],
            'actions' => [
                'edit' => [
                    'route_name' => "fsi_admin_crud_edit",
                    'additional_parameters' => ['element' => $datagrid->getName()],
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

        NewsDataSourceBuilder::buildNewsDataSource($datasource);

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        return $factory->createNamed('news', new NewsType(), $data);
    }
}
