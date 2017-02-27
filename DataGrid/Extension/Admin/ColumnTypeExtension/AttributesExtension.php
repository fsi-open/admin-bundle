<?php

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class AttributesExtension extends ColumnAbstractTypeExtension
{
    /**
     * @inheritdoc
     */
    public function getExtendedColumnTypes()
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
            'fsi_image'
        ];
    }

    /**
     * @inheritdoc
     */
    public function initOptions(ColumnTypeInterface $column)
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

    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $view->setAttribute('cell_attr', $column->getOption('cell_attr'));
        $view->setAttribute('container_attr', $column->getOption('container_attr'));
        $view->setAttribute('value_attr', $column->getOption('value_attr'));
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $view->setAttribute('header_attr', $column->getOption('header_attr'));
    }
}
