<?php

declare(strict_types=1);

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
