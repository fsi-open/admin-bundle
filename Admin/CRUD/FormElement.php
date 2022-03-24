<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

interface FormElement extends DataIndexerElement, RedirectableElement
{
    /**
     * @param mixed $data
     * @return FormInterface<string,FormInterface>
     */
    public function createForm($data = null): FormInterface;

    public function setFormFactory(FormFactoryInterface $factory): void;

    /**
     * This method is called after successful form submission and validation in edit and create action.
     * Mostly this method should create or update the object in your persistence layer.
     *
     * @param mixed $data
     */
    public function save($data): void;
}
