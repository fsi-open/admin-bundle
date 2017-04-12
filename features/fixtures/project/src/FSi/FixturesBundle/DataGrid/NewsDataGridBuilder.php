<?php
/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\FixturesBundle\DataGrid;

use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Component\DataGrid\DataGridInterface;

class NewsDataGridBuilder
{
    public static function buildNewsDataGrid(DataGridInterface $datagrid)
    {
        $dateType = TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\DateType', 'date');
        $datagrid->addColumn('title', 'text', [
            'label' => 'admin.news.list.title',
            'field_mapping' => ['title', 'subtitle'],
            'value_glue' => '<br/>',
            'editable' => true
        ]);

        $datagrid->addColumn('date', 'datetime', [
            'label' => 'admin.news.list.date',
            'datetime_format' => 'Y-m-d',
            'editable' => true,
            'form_type' => ['date' => $dateType],
            'form_options' => [
                'date' => ['widget' => 'single_text']
            ]
        ]);

        $datagrid->addColumn('created_at', 'datetime', [
            'label' => 'admin.news.list.created_at'
        ]);

        $datagrid->addColumn('visible', 'boolean', [
            'label' => 'admin.news.list.visible'
        ]);

        $datagrid->addColumn('creator_email', 'text', [
            'label' => 'admin.news.list.creator_email'
        ]);

        $datagrid->addColumn('photo', 'fsi_image', [
            'label' => 'admin.news.list.photo',
            'width' => 100
        ]);

        return $datagrid;
    }
}
