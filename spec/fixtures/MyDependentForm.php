<?php

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\DependentFormElement;
use Symfony\Component\Form\FormFactoryInterface;

class MyDependentForm extends DependentFormElement
{
    public function getId()
    {
    }

    public function getParentId()
    {
    }

    public function getDataIndexer()
    {
    }

    public function save($object)
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
    }
}
