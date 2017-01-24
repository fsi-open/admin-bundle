<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Core;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;
use FSi\Component\DataGrid\Extension\Core\EventSubscriber;

class CoreExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\Text(),
            new ColumnType\Number(),
            new ColumnType\Collection(),
            new ColumnType\DateTime(),
            new ColumnType\Money(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return [
            new ColumnTypeExtension\DefaultColumnOptionsExtension(),
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadSubscribers()
    {
        return [
            new EventSubscriber\ColumnOrder(),
        ];
    }
}
