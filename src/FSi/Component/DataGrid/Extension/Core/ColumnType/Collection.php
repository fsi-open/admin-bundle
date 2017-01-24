<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Collection extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'collection';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $value = (array) $value;
        foreach ($value as &$val) {
            if (!is_array($val)) {
                continue;
            }

            $val = implode($this->getOption('collection_glue'), $val);
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults([
            'collection_glue' => ' '
        ]);

        $this->getOptionsResolver()->setAllowedTypes('collection_glue', 'string');
    }
}
