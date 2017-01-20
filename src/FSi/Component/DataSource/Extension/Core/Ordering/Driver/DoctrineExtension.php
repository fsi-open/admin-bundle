<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Core\Ordering\Driver;

use FSi\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use \FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineFieldInterface as DoctrineORMFieldInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DriverEvent\DriverEventArgs;
use FSi\Component\DataSource\Driver\Doctrine\DoctrineAbstractField;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;

/**
 * Driver extension for ordering that loads fields extension.
 */
class DoctrineExtension extends DriverExtension implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array(
            'doctrine', // deprecated since version 1.4
            'doctrine-orm'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function loadFieldTypesExtensions()
    {
        return array(
            new FieldExtension(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            DriverEvents::PRE_GET_RESULT => array('preGetResult'),
        );
    }

    /**
     * @param \FSi\Component\DataSource\Driver\Doctrine\DoctrineAbstractField $field
     * @param string $alias
     * @throws \InvalidArgumentException
     * @return string
     */
    protected function getFieldName($field, $alias)
    {
        if (!$field instanceof DoctrineFieldInterface &&
            !$field instanceof DoctrineORMFieldInterface) {
            throw new \InvalidArgumentException("Field must be an instance of DoctrineField");
        }

        if ($field->hasOption('field')) {
            $name = $field->getOption('field');
        } else {
            $name = $field->getName();
        }

        if ($field->getOption('auto_alias') && !preg_match('/\./', $name)) {
            $name = "$alias.$name";
        }

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function preGetResult(DriverEvent\DriverEventArgs $event)
    {
        $fields = $event->getFields();
        $sortedFields = $this->sortFields($fields);

        $driver = $event->getDriver();
        $qb = $driver->getQueryBuilder();
        foreach ($sortedFields as $fieldName => $direction) {
            $field = $fields[$fieldName];
            $qb->addOrderBy($this->getFieldName($field, $driver->getAlias()), $direction);
        }
    }
}
