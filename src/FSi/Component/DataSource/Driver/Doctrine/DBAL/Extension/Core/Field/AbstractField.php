<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Doctrine\DBAL\Extension\Core\Field;

use Doctrine\DBAL\Query\QueryBuilder;
use FSi\Component\DataSource\Driver\Doctrine\DBAL\DoctrineField;
use FSi\Component\DataSource\Field\FieldAbstractType;

abstract class AbstractField extends FieldAbstractType implements DoctrineField
{
    /**
     * @param QueryBuilder $queryBuilder
     * @throws \FSi\Component\DataSource\Exception\FieldException
     */
    public function buildQuery(QueryBuilder $queryBuilder)
    {
        $data = $this->getCleanParameter();
        $name = $this->getName();

        if (($data === []) || ($data === '') || ($data === null)) {
            return;
        }

        $comparison = $this->getComparison();
        $func = sprintf('and%s', ucfirst($this->getOption('clause')));

        if (in_array($comparison, ['like'], true)) {
            $data = "%$data%";
            $comparison = 'like';
        }

        if ($comparison == 'isNull') {
            $queryBuilder->$func($this->getOption('field') . ' IS ' . ($data === 'null' ? '' : 'NOT ') . 'NULL');
            return;
        }

        $queryBuilder->$func($queryBuilder->expr()->$comparison($this->getOption('field'), ":$name"));
        $queryBuilder->setParameter($name, $data);
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $field = $this;
        $this->getOptionsResolver()
            ->setDefaults([
                'field' => null,
                'auto_alias' => true,
                'clause' => 'where'
            ])
            ->setAllowedValues('clause', ['where', 'having'])
            ->setAllowedTypes('field', ['string', 'null'])
            ->setAllowedTypes('auto_alias', 'bool')
            ->setNormalizer('field', function ($options, $value) use ($field) {
                if (!isset($value) && $field->getName()) {
                    return $field->getName();
                } else {
                    return $value;
                }
            })
            ->setNormalizer('clause', function ($options, $value) {
                return strtolower($value);
            });
    }
}
