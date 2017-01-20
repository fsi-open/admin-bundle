<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\ORM\Extension\Core\Field;

use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineAbstractField;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use Doctrine\ORM\QueryBuilder;

/**
 * Entity field.
 */
class Entity extends DoctrineAbstractField
{
    /**
     * {@inheritdoc}
     */
    protected $comparisons = array('eq', 'memberof', 'in', 'isNull');

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \FSi\Component\DataSource\Driver\Doctrine\Exception\DoctrineDriverException
     */
    public function buildQuery(QueryBuilder $qb, $alias)
    {
        $data = $this->getCleanParameter();
        $fieldName = $this->getFieldName($alias);
        $name = $this->getName();

        if (empty($data)) {
            return;
        }

        $comparison = $this->getComparison();
        $func = sprintf('and%s', ucfirst($this->getOption('clause')));

        switch ($comparison) {
            case 'eq':
                $qb->$func($qb->expr()->eq($fieldName, ":$name"));
                $qb->setParameter($name, $data);
                break;

            case 'memberof':
                $qb->$func(":$name MEMBER OF $fieldName");
                $qb->setParameter($name, $data);
                break;

            case 'in':
                $qb->$func("$fieldName IN (:$name)");
                $qb->setParameter($name, $data);
                break;

            case 'isNull':
                $qb->$func($fieldName . ' IS ' . ($data === 'null' ? '' : 'NOT ') . 'NULL');
                break;

            default:
                throw new DoctrineDriverException(sprintf('Unexpected comparison type ("%s").', $comparison));
        }
    }
}
