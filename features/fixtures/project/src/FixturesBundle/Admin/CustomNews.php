<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\FixturesBundle\Entity;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class CustomNews extends CRUDElement
{
    public function getId(): string
    {
        return 'custom_news';
    }

    public function getClassName(): string
    {
        return Entity\News::class;
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        return $factory->createDataGrid($this->getId());
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        return $factory
            ->createDataSource('doctrine-orm', ['entity' => $this->getClassName()], $this->getId())
            ->addField('title', 'text', 'eq', ['form_filter' => false]);
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        return $factory->createNamedBuilder(
            'news',
            FormType::class,
            $data,
            ['data_class' => $this->getClassName()]
        )->getForm();
    }
}
