<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core;

use FSi\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;
use FSi\Component\DataSource\Driver\DriverAbstractExtension;
use Symfony\Component\Form\FormFactory;

final class CoreExtension extends DriverAbstractExtension
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @param FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @return array
     */
    public function getExtendedDriverTypes()
    {
        return ['doctrine-dbal'];
    }

    /**
     * @return array
     */
    protected function loadFieldTypes()
    {
        return [
            new Field\TextField(),
            new Field\NumberField(),
            new Field\DateTimeField(),
            new Field\BooleanField(),
        ];
    }

    protected function loadFieldTypesExtensions()
    {
        return [
            new Field\FormFieldExtension($this->formFactory),
        ];
    }
}
