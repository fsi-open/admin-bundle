<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement;
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
