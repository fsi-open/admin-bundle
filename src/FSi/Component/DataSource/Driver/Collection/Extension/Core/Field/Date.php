<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Collection\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Collection\CollectionAbstractField;

/**
 * Date field.
 */
class Date extends CollectionAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'between');

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'date';
    }
}
