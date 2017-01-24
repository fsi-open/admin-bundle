<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

abstract class GenericDeleteElement extends GenericBatchElement implements DeleteElement
{
    /**
     * @inheritdoc
     */
    public function apply($object)
    {
        $this->delete($object);
    }
}
