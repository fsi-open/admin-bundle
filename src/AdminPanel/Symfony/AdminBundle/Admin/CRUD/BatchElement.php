<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;

interface BatchElement extends DataIndexerElement, RedirectableElement
{
    /**
     * This method is called from BatchController after action is confirmed.
     *
     * @param mixed $object
     */
    public function apply($object);
}
