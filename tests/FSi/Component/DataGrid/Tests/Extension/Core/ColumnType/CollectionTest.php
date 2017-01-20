<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_filter_value()
    {
        $column = new Collection();
        $column->initOptions();
        $column->setOption('collection_glue', ' ');
        $value = array(
            array('foo', 'bar'),
            'test'
        );

        $this->assertSame(
            array('foo bar', 'test'),
            $column->filterValue($value)
        );
    }
}
