<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests;

use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\Tests\Fixtures\FooExtension;

class DataGridFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    protected function setUp()
    {
        $extensions = array(
            new FooExtension(),
        );

        $dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $indexingStrategy = $this->getMock('FSi\Component\DataGrid\Data\IndexingStrategyInterface');

        $this->factory = new DataGridFactory($extensions, $dataMapper, $indexingStrategy);
    }

    public function testCreateGrids()
    {
        $grid = $this->factory->createDataGrid();
        $this->assertSame('grid',$grid->getName());

        $this->setExpectedException('FSi\Component\DataGrid\Exception\DataGridColumnException');
        $grid = $this->factory->createDataGrid('grid');
    }

    public function testHasColumnType()
    {
        $this->assertTrue($this->factory->hasColumnType('foo'));
        $this->assertFalse($this->factory->hasColumnType('bar'));
    }

    public function testGetColumntype()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\Tests\Fixtures\ColumnType\FooType', $this->factory->getColumnType('foo'));

        $this->setExpectedException('FSi\Component\DataGrid\Exception\UnexpectedTypeException');
        $this->factory->getColumnType('bar');
    }

    public function testGetDataMapper()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\DataMapper\DataMapperInterface', $this->factory->getDataMapper());
    }

    public function testGetIndexingStrategy()
    {
        $this->assertInstanceOf('FSi\Component\DataGrid\Data\IndexingStrategyInterface', $this->factory->getIndexingStrategy());
    }
}
