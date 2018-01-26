<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class AttributesExtension extends ColumnAbstractTypeExtension
{
    public function getExtendedColumnTypes(): array
    {
        return [
            'text',
            'boolean',
            'datetime',
            'money',
            'number',
            'entity',
            'collection',
            'action',
            'fsi_file',
            'fsi_image',
            'gedmo_tree'
        ];
    }

    public function initOptions(ColumnTypeInterface $column): void
    {
        $column->getOptionsResolver()->setDefined(['header_attr', 'cell_attr', 'container_attr', 'value_attr']);
        $column->getOptionsResolver()->setAllowedTypes('header_attr', 'array');
        $column->getOptionsResolver()->setAllowedTypes('cell_attr', 'array');
        $column->getOptionsResolver()->setAllowedTypes('container_attr', 'array');
        $column->getOptionsResolver()->setAllowedTypes('value_attr', 'array');
        $column->getOptionsResolver()->setDefaults([
            'header_attr' => [],
            'cell_attr' => [],
            'container_attr' => [],
            'value_attr' => []
        ]);
    }

    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view): void
    {
        $view->setAttribute('cell_attr', $column->getOption('cell_attr'));
        $view->setAttribute('container_attr', $column->getOption('container_attr'));
        $view->setAttribute('value_attr', $column->getOption('value_attr'));
    }

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view): void
    {
        $view->setAttribute('header_attr', $column->getOption('header_attr'));
    }
}
