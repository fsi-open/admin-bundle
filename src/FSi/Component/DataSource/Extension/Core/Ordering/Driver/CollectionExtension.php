<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Core\Ordering\Driver;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FSi\Component\DataSource\Event\DriverEvent\DriverEventArgs;
use FSi\Component\DataSource\Extension\Core\Ordering\Field\FieldExtension;
use FSi\Component\DataSource\Event\DriverEvents;
use FSi\Component\DataSource\Event\DriverEvent;

/**
 * Driver extension for ordering that loads fields extension.
 */
class CollectionExtension extends DriverExtension implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public function getExtendedDriverTypes()
    {
        return array('collection');
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
     * {@inheritdoc}
     */
    public function preGetResult(DriverEvent\DriverEventArgs $event)
    {
        $fields = $event->getFields();
        $sortedFields = $this->sortFields($fields);

        $driver = $event->getDriver();
        $c = $driver->getCriteria();
        $orderings = array();
        foreach ($sortedFields as $fieldName => $direction) {
            $field = $fields[$fieldName];
            $fieldName = $field->hasOption('field')?$field->getOption('field'):$field->getName();
            $orderings[$fieldName] = strtoupper($direction);
        }
        $c->orderBy($orderings);
    }
}
