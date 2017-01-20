<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\ValueFormatColumnOptionsExtension;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;

class ValueFormatColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function test_build_cell_view()
    {
        $extension = new ValueFormatColumnOptionsExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_glue':
                        return '-';
                        break;
                    case 'empty_value':
                        return '';
                        break;
                    case 'field_mapping':
                        return array();
                        break;
                }
            }));

        $view->expects($this->any(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->any(1))
            ->method('setValue')
            ->with('foo-bar');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_without_format_and_glue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_glue':
                case 'value_format':
                    return null;
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('foo');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_and_glue()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '<b>%s</b>';
                    break;
                case 'value_glue':
                    return '<br/>';
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_build_cell_view_without_format_and_glue_with_value_array()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_View_with_valid_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '<b>%s</b>';
                    break;
                case 'value_glue':
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_valid_format_and_value_array()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '<b>%s</b><br/><b>%s</b>';
                    break;
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b><br/><b>bar</b>');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException \PHPUnit_Framework_Error
     */
    public function test_build_cell_view_with_format_that_have_too_many_placeholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '%s%s';
                    break;
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_that_have_not_enough_placeholders()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '<b>%s</b>';
                    break;
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo', 'bar')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('<b>foo</b>');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '';
                    break;
                case 'value_glue':
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array('foo')));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_without_empty_value()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return null;
                    break;
                case 'value_glue':
                    return ' ';
                    break;
                case 'empty_value':
                    return '';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(null)));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_value()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return 'empty';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(null)));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('empty');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_empty_value_and_multiple_values()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return null;
                    break;
                case 'value_glue':
                    return ' ';
                    break;
                case 'empty_value':
                    return 'empty';
                    break;
                case 'field_mapping':
                    return array();
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                    'val',
                    '',
                    null,
                )
            ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('val empty empty');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_multiple_empty_value_and_multiple_values()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return array(
                        'fo' => 'foo',
                        'ba' => 'bar'
                    );
                    break;
                case 'field_mapping':
                    return array('fo', 'ba');
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue('default'));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('default');

        $extension->buildCellView($column, $view);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_build_cell_view_with_empty_value_that_not_exists_in_mapping_fields()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return array(
                        'fo' => 'empty',
                    );
                    break;
                case 'field_mapping':
                    return array('fos');
                    break;
            }
        }));

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_multiple_empty_value_multiple_values_and_template()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
            switch($option) {
                case 'value_format':
                    return '"%s" "%s" "%s"';
                    break;
                case 'value_glue':
                    return null;
                    break;
                case 'empty_value':
                    return array(
                        'fo' => 'empty-fo',
                        'ba' => 'empty-bar'
                    );
                    break;
                case 'field_mapping':
                    return array('fo', 'ba', 'ca');
                    break;
            }
        }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                    'fo' => '',
                    'ba' => '',
                    'ca' => null,
                )
            ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('"empty-fo" "empty-bar" ""');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_format_that_is_clousure()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_format':
                        return function($data) {
                            return $data['fo'] . '-' . $data['ba'];
                        };
                        break;
                    case 'value_glue':
                        return null;
                        break;
                    case 'empty_value':
                        return array();
                        break;
                    case 'field_mapping':
                        return array('fo', 'ba');
                        break;
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                'fo' => 'fo',
                'ba' => 'ba',
            )
        ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with('fo-ba');

        $extension->buildCellView($column, $view);
    }

    public function test_build_cell_view_with_value_that_is_zero()
    {
        $extension = new ValueFormatColumnOptionsExtension();
        $view = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');
        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');

        $column->expects($this->any())
            ->method('getOption')
            ->will($this->returnCallback(function($option) {
                switch($option) {
                    case 'value_glue':
                        return '';
                        break;
                    case 'empty_value':
                        return 'This should not be used.';
                        break;
                    case 'field_mapping':
                        return array('fo');
                        break;
                }
            }));

        $view->expects($this->at(0))
            ->method('getValue')
            ->will($this->returnValue(array(
                    'fo' => 0,
                )
            ));

        $view->expects($this->at(1))
            ->method('setValue')
            ->with(0);

        $extension->buildCellView($column, $view);
    }

    public function test_set_value_format_that_is_clousure()
    {
        $column = new Text();
        $extension = new ValueFormatColumnOptionsExtension();
        $column->addExtension($extension);

        $column->initOptions();
        $extension->initOptions($column);

        $column->setOptions(array(
            'value_format' => function($data) {
                return (string) $data;
            }
        ));

        $extension->filterValue($column, array('for' => 'bar'));
    }
}
