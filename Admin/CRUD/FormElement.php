<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use Symfony\Component\Form\FormFactoryInterface;

interface FormElement extends DataIndexerElement, RedirectableElement
{
    /**
     * @param mixed $data
     * @return \Symfony\Component\Form\Form|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createForm($data = null);

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function setFormFactory(FormFactoryInterface $factory);

    /**
     * This method is called from FormController after form validation is passed in edit and create action.
     * Mostly this method should save updated object in database.
     *
     * @param mixed $object
     */
    public function save($object);
}
