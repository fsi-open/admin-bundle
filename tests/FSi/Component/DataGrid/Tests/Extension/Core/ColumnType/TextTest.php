<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testTrimOption()
    {
        $column = new Text();
        $column->initOptions();
        $column->setOption('trim', true);

        $value = [
            ' VALUE ',
        ];

        $this->assertSame(
            ['VALUE'],
            $column->filterValue($value)
        );
    }
}
