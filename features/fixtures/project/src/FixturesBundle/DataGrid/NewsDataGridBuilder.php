<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\FixturesBundle\DataGrid;

use FSi\Component\DataGrid\DataGridInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class NewsDataGridBuilder
{
    public static function buildNewsDataGrid(DataGridInterface $datagrid): DataGridInterface
    {
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
            'form_type' => ['date' => DateType::class],
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
