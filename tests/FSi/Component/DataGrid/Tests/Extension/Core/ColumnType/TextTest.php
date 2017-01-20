<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;

class TextTest extends \PHPUnit_Framework_TestCase
{
    public function testTrimOption()
    {
        $column = new Text();
        $column->initOptions();
        $column->setOption('trim', true);

        $value = array(
            ' VALUE ',
        );

        $this->assertSame(
            array('VALUE'),
            $column->filterValue($value)
        );
    }
}
