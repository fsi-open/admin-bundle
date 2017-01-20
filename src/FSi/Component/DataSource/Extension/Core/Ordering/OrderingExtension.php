<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return array(
            new Driver\DoctrineExtension(),
            new Driver\CollectionExtension(),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function loadSubscribers()
    {
        return array(
            new EventSubscriber\Events(),
        );
    }
}
