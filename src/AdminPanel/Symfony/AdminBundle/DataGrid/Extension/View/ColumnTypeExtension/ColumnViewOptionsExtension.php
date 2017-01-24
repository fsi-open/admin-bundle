<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataGrid\Extension\View\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;

class ColumnViewOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritDoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $view->setAttribute('translation_domain', $column->getOption('translation_domain'));
    }

    /**
     * {@inheritDoc}
     */
    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $view->setAttribute('translation_domain', $column->getOption('translation_domain'));
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedColumnTypes()
    {
        return [
            'action',
            'boolean',
            'text',
            'datetime',
            'number',
            'money',
            'gedmo.tree',
            'entity',
            'collection',
            'action'
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults([
            'translation_domain' => 'messages',
        ]);

        $column->getOptionsResolver()->setAllowedTypes('translation_domain', [
            'string' ,
            'null'
        ]);
    }
}
