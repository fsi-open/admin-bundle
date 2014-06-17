<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use Symfony\Component\Form\FormFactoryInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             FSi\Bundle\AdminBundle\Admin\CRUD\FormElement instead.
 */
interface FormAwareInterface
{
    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function setFormFactory(FormFactoryInterface $factory);
}