<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField;

/**
 * Text field.
 */
class Text extends DoctrineAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq', 'neq', 'in', 'notIn', 'like', 'contains', 'isNull'];

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'text';
    }
}
