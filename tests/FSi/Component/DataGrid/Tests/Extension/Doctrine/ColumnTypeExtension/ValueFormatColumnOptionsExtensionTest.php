<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Doctrine\ColumntypeExtension;

use FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension\ValueFormatColumnOptionsExtension;

class ValueFormatColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{

    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutOneValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => null,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue('no'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no Foo');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsStringAndWithoutValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => null,
                    'name' => null
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue('no'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutOneValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => null
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(array('name' => 'no')));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('1 no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndWithoutValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => null,
                    'name' => null
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(array('id' => 'no','name' => 'no')));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithGlueAndGlueMultipleAndEmptyValueAsArrayAndWithoutMultipleValues()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => null,
                    'name' => null
                ),
                1 => array(
                    'id' => null,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(array('id' => 'no','name' => 'no')));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue('<br />'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('no no<br />no Foo');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException \FSi\Component\DataGrid\Exception\DataGridException
     */
    public function testBuildCellViewWithGlueAndEmptyValueAsArrayAndNotFoundKeyInEmptyValue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => null,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('empty_value')
            ->will($this->returnValue(array('id2' => 'no','name' => 'no')));

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithoutFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(null));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue(null));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatAndGlue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
        ->method('getValue')
        ->will($this->returnValue(array(
            0 => array(
                'id' => 1,
                'name' => 'Foo'
            )
        )));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s)'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1)<br/>(Foo)');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testBuildCellViewWithFormatAndGlueWithToManyPlaceholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
        ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo'
                )
            )));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue('<br/>'));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s) (%s)'));

        $extension->buildCellView($column, $view);
    }

    public function testBuildCellViewWithFormatGlueAndGlueMultiple()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                0 => array(
                    'id' => 1,
                    'name' => 'Foo',
                ),
                1 => array(
                    'id' => 2,
                    'name' => 'Bar',
                )
            )));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('value_glue')
            ->will($this->returnValue(' '));

        $column->expects($this->at(2))
            ->method('getOption')
            ->with('value_format')
            ->will($this->returnValue('(%s)'));

        $column->expects($this->at(3))
            ->method('getOption')
            ->with('glue_multiple')
            ->will($this->returnValue('<br>'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('(1) (Foo)<br>(2) (Bar)');

        $extension->buildCellView($column, $view);
    }
}
