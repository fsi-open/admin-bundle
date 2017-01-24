<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Money
     */
    private $column;

    public function setUp()
    {
        $column = new Number();
        $column->setName('number');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testPrecision()
    {
        $value = [
            'number' => 10.123,
        ];

        $this->column->setOption('precision', 2);
        $this->column->setOption('round_mode', Number::ROUND_HALF_UP);

        $this->assertSame(
            $this->column->filterValue($value),
            [
                'number' => 10.12,
            ]
        );
    }

    public function testRoundMode()
    {
        $this->column->setOption('round_mode', Number::ROUND_HALF_UP);
        $this->assertSame(
            $this->column->filterValue([
                'number' => 10.123,
            ]),
            [
                'number' => 10.12,
            ]
        );

        $this->assertSame(
            $this->column->filterValue([
                'number' => 10.126,
            ]),
            [
                'number' => 10.13,
            ]
        );
    }

    public function testNumberFormat()
    {
        $this->assertEquals(
            [
                'number' => 12345678.1,
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format', true);

        $this->assertEquals(
            [
                'number' => '12,345,678.10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_decimals', 0);

        $this->assertEquals(
            [
                'number' => '12,345,678',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_decimals', 2);

        $this->assertEquals(
            [
                'number' => '12,345,678.10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->column->setOption('format_dec_point', ',');
        $this->column->setOption('format_thousands_sep', ' ');

        $this->assertEquals(
            [
                'number' => '12 345 678,10',
            ],
            $this->column->filterValue([
                'number' => 12345678.1,
            ])
        );

        $this->assertEquals(
            [
                'number' => '1 000,00',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );

        $this->column->setOption('format_decimals', 0);

        $this->assertEquals(
            [
                'number' => '1 000',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );

        $this->column->setOption('format', false);
        $this->assertEquals(
            [
                'number' => '1000',
            ],
            $this->column->filterValue([
                'number' => 1000,
            ])
        );
    }
}
