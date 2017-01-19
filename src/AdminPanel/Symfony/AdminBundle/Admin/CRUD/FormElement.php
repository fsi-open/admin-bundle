<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;

interface FormElement extends DataIndexerElement, RedirectableElement, FormAwareInterface
{
    /**
     * @param mixed $data
     * @return \Symfony\Component\Form\Form|null
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RuntimeException
     */
    public function createForm($data = null);

    /**
     * This method is called from FormController after form validation is passed in edit and create action.
     * Mostly this method should save updated object in database.
     *
     * @param mixed $object
     */
    public function save($object);
}
