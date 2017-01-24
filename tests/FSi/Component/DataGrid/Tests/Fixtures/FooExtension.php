<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures;

use FSi\Component\DataGrid\DataGridAbstractExtension;
use FSi\Component\DataGrid\Tests\Fixtures\ColumnType;

class FooExtension extends DataGridAbstractExtension
{
    protected function loadColumnTypes()
    {
        return [
            new ColumnType\FooType(),
        ];
    }
}
