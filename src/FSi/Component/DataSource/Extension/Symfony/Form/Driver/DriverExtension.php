<?php

declare(strict_types=1);

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
        return [
            'doctrine', //deprecated since version 1.4
            'doctrine-orm'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return [
            new Field\FormFieldExtension($this->formFactory),
        ];
    }
}
