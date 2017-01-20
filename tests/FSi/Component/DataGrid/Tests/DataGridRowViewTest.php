<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridRowView;

class DataGridRowViewTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateDataGridRowView()
    {
        $source = 'SOURCE';

        $dataGridView = $this->getMock('FSi\Component\DataGrid\DataGridViewInterface');

        $cellView = $this->getMock('FSi\Component\DataGrid\Column\CellViewInterface');

        $column = $this->getMock('FSi\Component\DataGrid\Column\ColumnTypeInterface');
        $column->expects($this->atLeastOnce())
                ->method('createCellView')
                ->with($source, 0)
                ->will($this->returnValue($cellView));

        $columns = array(
            'foo' =>$column
        );

        $gridRow = new DataGridRowView($dataGridView, $columns, $source, 0);
        $this->assertSame($gridRow->current(), $cellView);

        $this->assertSame($gridRow->getSource(), $source);
    }
}
