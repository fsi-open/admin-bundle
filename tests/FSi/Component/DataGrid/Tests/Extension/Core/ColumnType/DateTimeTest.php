<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class DateTimeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Component\DataGrid\Extension\Core\ColumnType\Action
     */
    private $column;

    public function setUp()
    {
        $column = new DateTime();
        $column->setName('datetime');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    public function testBasicFilterValue()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = array(
            'datetime' => $dateTimeObject
        );

        $this->column->setOption('field_mapping', array('datetime'));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );
    }

    public function testFilterValueWithNull()
    {
        $value = array(
            'datetime' => null
        );

        $this->column->setOptions(array(
        ));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => null
            )
        );

        foreach (array('datetime', 'string', 'timestamp') as $input_type) {

            $this->column->setOptions(array(
                'input_type' => $input_type
            ));

            $this->assertSame(
                $this->column->filterValue($value),
                array(
                    'datetime' => null
                )
            );
        }
    }

    public function testFormatOption()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $value = array(
            'datetime' => $dateTimeObject
        );

        $this->column->setOptions(array(
            'field_mapping' => array('datetime'),
            'datetime_format' => 'Y.d.m'
        ));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y.d.m')
            )
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testMappingFieldsOptionInputTimestamp()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $brokenValue = array(
            'datetime' => $dateTimeObject
        );
        $value = array(
            'datetime' => $dateTimeObject->getTimestamp()
        );

        $this->column->setOptions(array(
            'input_type' => 'timestamp',
        ));

        $this->column->filterValue($value);
        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );

        $this->column->filterValue($brokenValue);
    }

    /**
     * @expectedException \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    public function testMappingFieldsOptionInputStringMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        );

        $this->column->setOption('input_type', 'string');

        $this->column->filterValue($value);
    }

    /**
     * @expectedException \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    public function testMappingFieldsOptionInputString()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');

        $brokenValue = array(
            'datetime' => $dateTimeObject
        );

        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
        );

        $this->column->setOptions(array(
            'input_type' => 'string',
            'input_field_format' => 'Y-m-d H:i:s'
        ));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s')
            )
        );

        $this->column->filterValue($brokenValue);
    }

    /**
     * @expectedException \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    public function testMappingFieldsOptionInputArrayMissingMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');

        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        );

        $this->column->setOption('input_type', 'array');
        $this->column->filterValue($value);
    }

    /**
     * @expectedException \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    public function testMappingFieldsOptionInputArrayWrongMappingFieldsFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
            'time' => $dateObject->format('Y-m-d H:i:s')
        );

        $this->column->setOptions(array(
            'input_type' => 'string',
            'input_field_format' => array(
                'datetime' => 'string',
                'time' => 'string'
            )
        ));

        $this->column->filterValue($value);
    }

    public function testMappingFieldsOptionInputArray()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $this->column->setOptions(array(
            'input_type' => 'array',
            'input_field_format' => array(
                'datetime' => array('input_type' => 'datetime'),
                'time' => array('input_type' => 'datetime'),
                'string' => array('input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'),
                'timestamp' => array('input_type' => 'timestamp')
            )
        ));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y-m-d H:i:s'),
                'time' => $dateObject->format('Y-m-d 00:00:00'),
                'string' => $dateTimeObject->format('Y-m-d H:i:s'),
                'timestamp' => date('Y-m-d H:i:s', $dateTimeObject->getTimestamp()),
            )
        );
    }

    public function testMappingFieldsOptionInputArrayWithFormat()
    {
        $dateTimeObject = new \DateTime('2012-05-03 12:41:11');
        $dateObject = new \DateTime('2012-05-03');
        $value = array(
            'datetime' => $dateTimeObject,
            'time' => $dateObject,
            'string' => $dateTimeObject->format('Y-m-d H:i:s'),
            'timestamp' => $dateTimeObject->getTimestamp()
        );

        $this->column->setOptions(array(
            'input_type' => 'array',
            'datetime_format' => 'Y.d.m',
            'input_field_format' => array(
                'datetime' => array('input_type' => 'datetime'),
                'time' => array('input_type' => 'datetime'),
                'string' => array('input_type' => 'string', 'datetime_format' => 'Y-m-d H:i:s'),
                'timestamp' => array('input_type' => 'timestamp')
            )
        ));

        $this->assertSame(
            $this->column->filterValue($value),
            array(
                'datetime' => $dateTimeObject->format('Y.d.m'),
                'time' => $dateObject->format('Y.d.m'),
                'string' => $dateTimeObject->format('Y.d.m'),
                'timestamp' => $dateTimeObject->format('Y.d.m')
            )
        );
    }
}
