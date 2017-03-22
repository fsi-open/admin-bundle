<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class CustomNews extends CRUDElement
{
    public function getId()
    {
        return 'custom_news';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\News';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        /* @var $datagrid \FSi\Component\DataGrid\DataGrid */
        $datagrid = $factory->createDataGrid('custom_news');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        /* @var $datasource \FSi\Component\DataSource\DataSource */
        $datasource = $factory->createDataSource('doctrine', ['entity' => $this->getClassName()], 'custom_news');

        $datasource->addField('title', 'text', 'eq', ['form_filter' => false]);

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

        return $builder->getForm();
    }
}
