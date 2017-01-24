<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\ORM;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface for Doctrine driver's fields.
 */
interface DoctrineFieldInterface
{
    /**
     * Builds query.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     */
    public function buildQuery(QueryBuilder $qb, $alias);

    /**
     * Returns DQL type that this field's value will be casted to.
     *
     * @return null|string
     */
    public function getDBALType();
}
