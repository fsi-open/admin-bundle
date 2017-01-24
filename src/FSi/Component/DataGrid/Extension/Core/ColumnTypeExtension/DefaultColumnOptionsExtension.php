<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class DefaultColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $view->setLabel($column->getOption('label'));
        if (!is_null($order = $column->getOption('display_order'))) {
            $view->setAttribute('display_order', $order);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedColumnTypes()
    {
        return [
            'batch',
            'text',
            'boolean',
            'collection',
            'datetime',
            'number',
            'money',
            'gedmo_tree',
            'entity',
            'action',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults([
            'label' => $column->getName(),
            'display_order' => null,
            'field_mapping' => [$column->getName()]
        ]);

        $column->getOptionsResolver()->setAllowedTypes('label', 'string');
        $column->getOptionsResolver()->setAllowedTypes('field_mapping', 'array');
        $column->getOptionsResolver()->setAllowedTypes('display_order', ['integer', 'null']);
    }
}
