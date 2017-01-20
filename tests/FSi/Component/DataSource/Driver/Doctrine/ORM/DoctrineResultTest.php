<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Driver\Doctrine\ORM;

use FSi\Component\DataSource\Driver\Doctrine\ORM\DoctrineResult;

class DoctrineResultTest extends \PHPUnit_Framework_TestCase
{
    public function testEmptyPaginator()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $paginator = $this->getMockBuilder('Doctrine\ORM\Tools\Pagination\Paginator')
            ->disableOriginalConstructor()
            ->getMock();

        $paginator->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(array()));

        $result = new DoctrineResult($registry, $paginator);
    }

    public function testResultWithNotObjectDataInRows()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $paginator = $this->getMockBuilder('Doctrine\ORM\Tools\Pagination\Paginator')
            ->disableOriginalConstructor()
            ->getMock();

        $paginator->expects($this->any())
            ->method('getIterator')
            ->will($this->returnValue(array(
                '0' => array(
                    'foo',
                    'bar'
                ),
                '1' => array(
                    'foo1',
                    'bar1'
                )
            )));

        $result = new DoctrineResult($registry, $paginator);
        $this->assertSame($result['0'], array(
            'foo',
            'bar'
        ));
        $this->assertSame($result['1'], array(
            'foo1',
            'bar1'
        ));
    }
}
