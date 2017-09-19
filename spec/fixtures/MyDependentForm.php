<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\DependentFormElement;
use FSi\Component\DataIndexer\DataIndexerInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MyDependentForm extends DependentFormElement
{
    public function getId(): string
    {
        return 'my_dependent_form';
    }

    public function getParentId(): string
    {
    }

    public function getDataIndexer(): DataIndexerInterface
    {
    }

    public function save($object): void
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
    }
}
