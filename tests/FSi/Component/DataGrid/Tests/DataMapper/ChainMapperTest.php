<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\DataMapper;

use FSi\Component\DataGrid\DataMapper\ChainMapper;
use FSi\Component\DataGrid\Exception\DataMappingException;

class ChainMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testMappersInChainWithInvalidMappers()
    {
        $this->setExpectedException('InvalidArgumentException');
        $chain = new ChainMapper(array(
            'foo',
            'bar'
        ));
    }

    public function testMappersInChainWithEmptyMappersArray()
    {
        $this->setExpectedException('InvalidArgumentException');
        $chain = new ChainMapper(array(
            'foo',
            'bar'
        ));
    }

    public function testGetDataFromTwoMappers()
    {
        $mapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $mapper1 = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $mapper->expects($this->once())
               ->method('getData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('getData')
               ->will($this->returnValue('foo'));

        $chain = new ChainMapper(array($mapper, $mapper1));

        $this->assertSame(
            'foo',
            $chain->getData('foo', 'bar')
        );
    }

    public function testSetDataWithTwoMappers()
    {
        $mapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
        $mapper1 = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $mapper->expects($this->once())
               ->method('setData')
               ->will($this->throwException(new DataMappingException));

        $mapper1->expects($this->once())
               ->method('setData')
               ->with('foo', 'bar', 'test')
               ->will($this->returnValue(true));

        $chain = new ChainMapper(array($mapper, $mapper1));

        $this->assertTrue($chain->setData('foo', 'bar', 'test'));
    }
}
