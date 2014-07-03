<?php

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement;
use Symfony\Component\Form\FormFactoryInterface;

class MyForm extends GenericFormElement
{
    public function getId()
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
