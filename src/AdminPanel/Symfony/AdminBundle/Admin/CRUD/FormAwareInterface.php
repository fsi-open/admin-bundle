<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement instead.
 */
interface FormAwareInterface
{
    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function setFormFactory(FormFactoryInterface $factory);
}
