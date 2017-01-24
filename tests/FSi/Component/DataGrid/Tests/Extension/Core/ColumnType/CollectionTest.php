<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_filter_value()
    {
        $column = new Collection();
        $column->initOptions();
        $column->setOption('collection_glue', ' ');
        $value = [
            ['foo', 'bar'],
            'test'
        ];

        $this->assertSame(
            ['foo bar', 'test'],
            $column->filterValue($value)
        );
    }
}
