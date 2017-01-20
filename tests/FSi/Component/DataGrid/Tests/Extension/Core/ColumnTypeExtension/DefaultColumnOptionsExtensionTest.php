<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumntypeExtension;

use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class DefaultColumnOptionsExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testBuildHeaderView()
    {
        $extension = new DefaultColumnOptionsExtension();

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $view = $this->getMock('FSi\Component\DataGrid\Column\HeaderViewInterface');

        $column->expects($this->at(0))
            ->method('getOption')
            ->with('label')
            ->will($this->returnValue('foo'));

        $column->expects($this->at(1))
            ->method('getOption')
            ->with('display_order')
            ->will($this->returnValue(100));

        $view->expects($this->at(0))
            ->method('setLabel')
            ->with('foo');

        $view->expects($this->at(1))
            ->method('setAttribute')
            ->with('display_order', 100);

        $extension->buildHeaderView($column, $view);
    }
}
