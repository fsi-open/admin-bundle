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
        return $factory->createDataGrid($this->getId());
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        return $factory->createDataSource(
            'doctrine-orm',
            ['entity' => $this->getClassName()],
            $this->getId()
        )->addField('title', 'text', 'eq', ['form_filter' => false]);
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        return $factory->createNamedBuilder(
            'news',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
            $data,
            ['data_class' => $this->getClassName()]
        )->getForm();
    }
}
