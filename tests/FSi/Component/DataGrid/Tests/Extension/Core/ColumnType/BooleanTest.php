<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;

class BooleanTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean
     */
    private $column;

    public function setUp()
    {
        $column = new Boolean();
        $column->setName('available');
        $column->initOptions();

        $this->column = $column;
    }

    public function testBasicFilterValue()
    {
        $this->column->setOptions(array(
            'true_value' => 'true',
            'false_value'=> 'false'
        ));

        $this->assertSame($this->column->filterValue(true), 'true');
        $this->assertNotSame($this->column->filterValue(true), 'false');
    }

    public function testFilterValueWithTrueValuesInArray()
    {
        $this->column->setOption('true_value', 'true');

        $this->assertSame(
            $this->column->filterValue(array(
                true,
                true
            )),
            'true'
        );
    }

    public function testFilterValueWithMixedValuesInArray()
    {
        $this->column->setOptions(array(
            'true_value' => 'true',
            'false_value'=> 'false'
        ));

        $this->assertSame(
            $this->column->filterValue(array(
                true,
                1,
                new \DateTime()
            )),
            'true'
        );

        $this->assertNotSame(
            $this->column->filterValue(array(
                true,
                1,
                new \DateTime()
            )),
            'false'
        );
    }


    public function testFilterValueWithFalseValuesInArray()
    {
        $this->column->setOptions(array(
            'true_value' => 'true',
            'false_value'=> 'false'
        ));

        $this->assertNotSame(
            $this->column->filterValue(array(
                false,
                false
            )),
            'true'
        );

        $this->assertSame(
            $this->column->filterValue(array(
                false,
                false
            )),
            'false'
        );
    }

    public function testFilterValueWithMixedValuesAndFalseInArray()
    {
        $this->column->setOptions(array(
            'true_value' => 'true',
            'false_value'=> 'false'
        ));

        $this->assertNotSame(
            $this->column->filterValue(array(
                true,
                1,
                new \DateTime(),
                false
            )),
            'true'
        );

        $this->assertSame(
            $this->column->filterValue(array(
                true,
                1,
                new \DateTime(),
                false
            )),
            'false'
        );
    }
}
