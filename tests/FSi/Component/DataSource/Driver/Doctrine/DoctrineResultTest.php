<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Tests\Driver\Doctrine;

use FSi\Component\DataSource\Driver\Doctrine\DoctrineResult;

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
            ->will($this->returnValue([]));

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
            ->will($this->returnValue([
                '0' => [
                    'foo',
                    'bar'
                ],
                '1' => [
                    'foo1',
                    'bar1'
                ]
            ]));

        $result = new DoctrineResult($registry, $paginator);
        $this->assertSame($result['0'], [
            'foo',
            'bar'
        ]);
        $this->assertSame($result['1'], [
            'foo1',
            'bar1'
        ]);
    }
}
