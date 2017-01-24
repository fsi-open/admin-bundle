<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class FooType extends ColumnAbstractType
{
    public function getId()
    {
        return 'foo';
    }

    public function filterValue($value)
    {
        return $value;
    }
}
