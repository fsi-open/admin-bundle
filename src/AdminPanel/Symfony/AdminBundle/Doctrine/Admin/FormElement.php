<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Doctrine\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericFormElement;

abstract class FormElement extends GenericFormElement implements Element
{
    use DataIndexerElementImpl;

    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
        $this->getObjectManager()->persist($object);
        $this->getObjectManager()->flush();
    }
}
