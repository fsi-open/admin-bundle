<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\Form\Driver;

use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use FSi\Component\DataSource\Extension\Symfony\Form\Field;
use Symfony\Component\Form\FormFactory;

/**
 * Driver extension for form that loads fields extension.
 */
class DriverExtension extends DriverAbstractExtension
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    private $formFactory;

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array(
            'doctrine', //deprecated since version 1.4
            'doctrine-orm'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return array(
            new Field\FormFieldExtension($this->formFactory),
        );
    }
}
