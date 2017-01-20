<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        $value = array(
            'number' => 10.123,
        );

        $this->column->setOption('precision', 2);
        $this->column->setOption('round_mode', Number::ROUND_HALF_UP);

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'number' => 10.12,
            )
        );
    }

    public function testRoundMode()
    {
        $this->column->setOption('round_mode', Number::ROUND_HALF_UP);
        $this->assertSame(
            $this->column->filterValue(array(
                'number' => 10.123,
            )),
            array(
                'number' => 10.12,
            )
        );

        $this->assertSame(
            $this->column->filterValue(array(
                'number' => 10.126,
            )),
            array(
                'number' => 10.13,
            )
        );
    }

    public function testNumberFormat()
    {
        $this->assertEquals(
            array(
                'number' => 12345678.1,
            ),
            $this->column->filterValue(array(
                'number' => 12345678.1,
            ))
        );

        $this->column->setOption('format', true);

        $this->assertEquals(
            array(
                'number' => '12,345,678.10',
            ),
            $this->column->filterValue(array(
                'number' => 12345678.1,
            ))
        );

        $this->column->setOption('format_decimals', 0);

        $this->assertEquals(
            array(
                'number' => '12,345,678',
            ),
            $this->column->filterValue(array(
                'number' => 12345678.1,
            ))
        );

        $this->column->setOption('format_decimals', 2);

        $this->assertEquals(
            array(
                'number' => '12,345,678.10',
            ),
            $this->column->filterValue(array(
                'number' => 12345678.1,
            ))
        );

        $this->column->setOption('format_dec_point', ',');
        $this->column->setOption('format_thousands_sep', ' ');

        $this->assertEquals(
            array(
                'number' => '12 345 678,10',
            ),
            $this->column->filterValue(array(
                'number' => 12345678.1,
            ))
        );

        $this->assertEquals(
            array(
                'number' => '1 000,00',
            ),
            $this->column->filterValue(array(
                'number' => 1000,
            ))
        );

        $this->column->setOption('format_decimals', 0);

        $this->assertEquals(
            array(
                'number' => '1 000',
            ),
            $this->column->filterValue(array(
                'number' => 1000,
            ))
        );

        $this->column->setOption('format', false);
        $this->assertEquals(
            array(
                'number' => '1000',
            ),
            $this->column->filterValue(array(
                'number' => 1000,
            ))
        );
    }
}
