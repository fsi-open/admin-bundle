<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver\Doctrine\ORM;

use FSi\Component\DataSource\Field\FieldAbstractType;
use FSi\Component\DataSource\Driver\Doctrine\ORM\Exception\DoctrineDriverException;
use Doctrine\ORM\QueryBuilder;

/**
 * {@inheritdoc}
 */
abstract class DoctrineAbstractField extends FieldAbstractType implements DoctrineFieldInterface
{
    /**
     * {@inheritdoc}
     */
    public function buildQuery(QueryBuilder $qb, $alias)
    {
        $data = $this->getCleanParameter();
        $fieldName = $this->getFieldName($alias);
        $name = $this->getName();

        if (($data === array()) || ($data === '') || ($data === null)) {
            return;
        }

        $type = $this->getDBALType();
        $comparison = $this->getComparison();
        $func = sprintf('and%s', ucfirst($this->getOption('clause')));

        if ($comparison == 'between') {
            if (!is_array($data)) {
                throw new DoctrineDriverException('Fields with \'between\' comparison require to bind an array.');
            }

            $from = array_shift($data);
            $to = array_shift($data);

            if (empty($from) && ($from !== 0)) {
                $from = null;
            }

            if (empty($to) && ($to !== 0)) {
                $to = null;
            }

            if ($from === null && $to === null) {
                return;
            } elseif ($from === null) {
                $comparison = 'lte';
                $data = $to;
            } elseif ($to === null) {
                $comparison = 'gte';
                $data = $from;
            } else {
                $qb->$func($qb->expr()->between($fieldName, ":{$name}_from", ":{$name}_to"));
                $qb->setParameter("{$name}_from", $from, $type);
                $qb->setParameter("{$name}_to", $to, $type);
                return;
            }
        }

        if ($comparison == 'isNull') {
            $qb->$func($fieldName . ' IS ' . ($data === 'null' ? '' : 'NOT ') . 'NULL');
            return;
        }

        if (in_array($comparison, array('in', 'notIn')) && !is_array($data)) {
            throw new DoctrineDriverException('Fields with \'in\' and \'notIn\' comparisons require to bind an array.');
        } elseif (in_array($comparison, array('like', 'contains'))) {
            $data = "%$data%";
            $comparison = 'like';
        }
        $qb->$func($qb->expr()->$comparison($fieldName, ":$name"));
        $qb->setParameter($this->getName(), $data, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $field = $this;
        $this->getOptionsResolver()
            ->setDefaults(array(
                'field' => null,
                'auto_alias' => true,
                'clause' => 'where'
            ))
            ->setAllowedValues('clause', array('where', 'having'))
            ->setAllowedTypes('field', array('string', 'null'))
            ->setAllowedTypes('auto_alias', 'bool')
            ->setNormalizer('field', function($options, $value) use ($field) {
                    if (!isset($value) && $field->getName()) {
                        return $field->getName();
                    } else {
                        return $value;
                    }
                })
            ->setNormalizer('clause', function($options, $value) {
                    return strtolower($value);
                }
            );
        ;
    }

    /**
     * Constructs proper field name from field mapping or (if absent) from own name.
     * Optionally adds alias (if missing and auto_alias option is set to true).
     *
     * @param string $alias
     * @return string
     */
    protected function getFieldName($alias)
    {
        $name = $this->getOption('field');

        if ($this->getOption('auto_alias') && !preg_match('/\./', $name)) {
            $name = "$alias.$name";
        }

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getDBALType()
    {
        return null;
    }
}
