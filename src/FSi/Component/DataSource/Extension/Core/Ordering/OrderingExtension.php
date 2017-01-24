<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Extension\Core\Ordering;

use FSi\Component\DataSource\DataSourceAbstractExtension;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\DataSourceViewInterface;
use FSi\Component\DataSource\Field\FieldTypeInterface;

/**
 * Ordering extension allows to set orderings for fetched data.
 *
 * It also sets proper ordering priority just before fetching data. It's up to driver
 * to 'catch' these priorities and make it work.
 */
class OrderingExtension extends DataSourceAbstractExtension
{
    /**
     * Key for passing data and ordering attribute.
     */
    const PARAMETER_SORT = 'sort';

    /**
     * {@inheritdoc}
     */
    public function loadDriverExtensions()
    {
        return [
            new Driver\DoctrineExtension(),
            new Driver\CollectionExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return [
            new EventSubscriber\Events(),
        ];
    }
}
