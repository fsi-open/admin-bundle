<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Money
     */
    private $column;

    public function setUp()
    {
        $column = new Money();
        $column->setName('money');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testCurrencyOption()
    {
        $value = array(
            'value' => 10,
        );

        $this->column->setOption('currency', 'PLN');

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.00 PLN',
            )
        );
    }

    public function testCurrencySeparatorOption()
    {
        $value = array(
            'value' => 10,
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('value_currency_separator', '$ ');

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.00$ PLN',
            )
        );
    }

    public function testCurrencyDecPointOption()
    {
        $value = array(
            'value' => 10,
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('dec_point', '-');

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10-00 PLN',
            )
        );
    }

    public function testCurrencyDecimalsOption()
    {
        $value = array(
            'value' => 10,
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 0);

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10 PLN',
            )
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('decimals', 5);

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.00000 PLN',
            )
        );
    }

    public function testCurrencyPrecisionOption()
    {
        $value = array(
            'value' => 10.326
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('precision', 2);

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.33 PLN',
            )
        );

        $value = array(
            'value' => 10.324,
        );
        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.32 PLN',
            )
        );
    }

    public function testCurrencyThousandsSepOption()
    {
        $value = array(
            'value' => 10000,
        );

        $this->column->setOption('currency', 'PLN');
        $this->column->setOption('thousands_sep', '.');

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'value' => '10.000.00 PLN',
            )
        );
    }
}
