<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;

use Doctrine\ORM\QueryBuilder;
use FSi\Component\DataSource\Driver\Doctrine\DoctrineFieldInterface;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field\Entity as BaseEntity;

/**
 * Entity field.
 * @deprecated since version 1.2
 */
class Entity extends BaseEntity implements DoctrineFieldInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException
     */
    public function buildQuery(QueryBuilder $qb, $alias)
    {
        try {
            parent::buildQuery($qb, $alias);
        } catch (DoctrineDriverException $e) {
            throw new \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }
}
