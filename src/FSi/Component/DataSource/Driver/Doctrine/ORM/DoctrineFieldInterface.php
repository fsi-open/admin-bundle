<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
