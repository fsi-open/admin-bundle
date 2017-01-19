<?php


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
