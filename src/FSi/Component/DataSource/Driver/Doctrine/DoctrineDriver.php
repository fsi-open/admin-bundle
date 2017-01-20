<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Pagination\Paginator;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineDriver as BaseDriver;

/**
 * @deprecated since version 1.2
 */
class DoctrineDriver extends BaseDriver
{
    public function __construct($extensions, EntityManager $em, $entity, $alias = null)
    {
        try {
            parent::__construct($extensions, $em, $entity, $alias);

        } catch (DoctrineDriverException $e) {
            throw new \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'doctrine';
    }

    /**
     * @param array $fields
     * @param int $first
     * @param int $max
     * @return \Countable|Paginator
     * @throws Exception\DoctrineDriverException
     */
    public function buildResult($fields, $first, $max)
    {
        try {
            return parent::buildResult($fields, $first, $max);
        } catch (DoctrineDriverException $e) {
            throw new \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     * @throws Exception\DoctrineDriverException
     */
    public function getQueryBuilder()
    {
        try {
            return parent::getQueryBuilder();
        } catch (DoctrineDriverException $e) {
            throw new \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException(
                $e->getMessage()
            );
        }
    }
}
