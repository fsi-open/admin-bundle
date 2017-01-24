<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Doctrine;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

class DoctrineExtension extends DataGridAbstractExtension
{
    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\Entity(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function loadColumnTypesExtensions()
    {
        return [
            new ColumnTypeExtension\ValueFormatColumnOptionsExtension(),
        ];
    }
}
