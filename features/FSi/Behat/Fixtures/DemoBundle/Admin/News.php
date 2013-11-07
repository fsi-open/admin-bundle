<?php

namespace FSi\Behat\Fixtures\DemoBundle\Admin;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
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
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
    }
}
