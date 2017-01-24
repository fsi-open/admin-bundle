<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;

final class BooleanField extends AbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = ['eq', 'isNull'];

    /**
     * @return string
     */
    public function getType()
    {
        return 'boolean';
    }
}
