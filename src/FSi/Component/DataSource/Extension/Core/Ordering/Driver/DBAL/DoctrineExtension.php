<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Core\Ordering\Driver\DBAL;

use FSi\Component\DataSource\Driver\Doctrine\DBAL\DoctrineField;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;
use FSi\Component\DataSource\Extension\Core\Ordering\Driver\DriverExtension;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\DBAL\FieldExtension;

class DoctrineExtension extends DriverExtension
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return [
            'doctrine-dbal',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return [
            new FieldExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            DriverEvents::PRE_GET_RESULT => ['preGetResult'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function preGetResult(DriverEvent\DriverEventArgs $event)
    {
        $fields = $event->getFields();
        $sortedFields = $this->sortFields($fields);

        if (count($sortedFields) === 0) {
            return;
        }

        $driver = $event->getDriver();

        $qb = $driver->getQueryBuilder();
        $qb->resetQueryPart('orderBy');

        foreach ($sortedFields as $fieldName => $direction) {
            $field = $fields[$fieldName];
            $qb->addOrderBy($this->getFieldName($field), $direction);
        }
    }

    /**
     * @param \FSi\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field\AbstractField $field
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getFieldName($field)
    {
        if (!$field instanceof DoctrineField) {
            throw new \InvalidArgumentException("Field must be an instance of DoctrineField");
        }

        if ($field->hasOption('field')) {
            $name = $field->getOption('field');
        } else {
            $name = $field->getName();
        }

        return $name;
    }
}
