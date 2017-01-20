<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DataGrid\Extension\View\ColumnTypeExtension;

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
        return array(
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
        );
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'translation_domain' => 'messages',
        ));

        $column->getOptionsResolver()->setAllowedTypes('translation_domain', array(
            'string' ,
            'null'
        ));
    }
}
