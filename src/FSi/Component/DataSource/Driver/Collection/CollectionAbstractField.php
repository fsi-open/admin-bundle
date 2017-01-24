<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver\Collection;

use FSi\Component\DataSource\Driver\Collection\Exception\CollectionDriverException;
use Doctrine\Common\Collections\Criteria;
use FSi\Component\DataSource\Field\FieldAbstractType;

/**
 * {@inheritdoc}
 */
abstract class CollectionAbstractField extends FieldAbstractType implements CollectionFieldInterface
{
    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $field = $this;
        $this->getOptionsResolver()
            ->setDefined(['field'])
            ->setAllowedTypes('field', ['string', 'null'])
            ->setNormalizer('field', function ($options, $value) use ($field) {
                if (!isset($value) && $field->getName()) {
                    return $field->getName();
                } else {
                    return $value;
                }
            });
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildCriteria(Criteria $c)
    {
        $data = $this->getCleanParameter();

        if (($data === []) || ($data === '') || ($data === null)) {
            return;
        }

        $type = $this->getPHPType();
        $field = $this->hasOption('field') ? $this->getOption('field') : $this->getName();
        $comparison = $this->getComparison();
        $eb = Criteria::expr();

        if ($comparison == 'between') {
            if (!is_array($data)) {
                throw new CollectionDriverException('Fields with \'between\' comparison require to bind an array.');
            }

            $from = count($data) ? array_shift($data) : null;
            $to = count($data) ? array_shift($data) : null;

            if (!$from && ($from !== 0)) {
                $from = null;
            }

            if (!$to && ($to !== 0)) {
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
                if (isset($type)) {
                    settype($from, $type);
                    settype($to, $type);
                }
                $c->andWhere($eb->andX($eb->lte($field, $to), $eb->gte($field, $from)));
                return;
            }
        }

        if (in_array($comparison, ['in', 'nin']) && !is_array($data)) {
            throw new CollectionDriverException('Fields with \'in\' and \'nin\' comparisons require to bind an array.');
        }

        if (isset($type)) {
            settype($data, $type);
        }
        $c->andWhere($eb->$comparison($field, $data));
    }

    /**
     * {@inheritdoc}
     */
    public function getPHPType()
    {
        return null;
    }
}
