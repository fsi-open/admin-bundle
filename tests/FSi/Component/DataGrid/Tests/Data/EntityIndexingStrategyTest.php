<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Data;

use FSi\Component\DataGrid\Tests\Fixtures\Entity;
use FSi\Component\DataGrid\Data\EntityIndexingStrategy;
use FSi\Component\DataGrid\Tests\Fixtures\EntityManagerMock;

class EntityIndexingStrategyTest extends \PHPUnit_Framework_TestCase
{
    protected $dataMapper;

    protected function setUp()
    {
        $this->dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');
    }

    public function testInvalidObject()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');

        $strategy = new EntityIndexingStrategy($registry);
        $this->assertSame(null, $strategy->getIndex('foo', $this->dataMapper));
    }

    public function testGetIndex()
    {
        $self = $this;

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->once())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() use ($self) {

                $metadataFactory = $self->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
                $metadataFactory->expects($self->once())
                    ->method('getMetadataFor')
                    ->will($self->returnCallback(function() use ($self) {
                        $classMetadata = $self->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                                ->disableOriginalConstructor()
                                ->getMock();

                        $classMetadata->expects($self->once())
                                ->method('getIdentifierFieldNames')
                                ->will($self->returnValue(array('id')));

                        return $classMetadata;
                   }));

                $em = new EntityManagerMock();
                $em->_setMetadataFactory($metadataFactory);

                return $em;
            }));

        $strategy = new EntityIndexingStrategy($registry);

        $this->dataMapper->expects($this->once())
            ->method('getData')
            ->will($this->returnValue('test'));

        $this->assertSame('test', $strategy->getIndex(new Entity('test'), $this->dataMapper));
    }

    public function testRevertIndex()
    {
        $self = $this;

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->once())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() use ($self) {

                $metadataFactory = $self->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
                $metadataFactory->expects($self->once())
                    ->method('getMetadataFor')
                    ->will($self->returnCallback(function() use ($self) {
                        $classMetadata = $self->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                            ->disableOriginalConstructor()
                            ->getMock();

                        $classMetadata->expects($self->once())
                            ->method('getIdentifierFieldNames')
                            ->will($self->returnValue(array('id')));

                        return $classMetadata;
                    }));

                $em = new EntityManagerMock();
                $em->_setMetadataFactory($metadataFactory);

                return $em;
            }));

        $index = 'test|id';

        $strategy = new EntityIndexingStrategy($registry);
        $this->assertSame(array('id' => 'test|id'), $strategy->revertIndex($index, 'Entity'));
    }

    public function testRevertIndexComposite()
    {
        $self = $this;

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $registry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() use ($self) {

                $metadataFactory = $self->getMock('Doctrine\ORM\Mapping\ClassMetadataFactory');
                $metadataFactory->expects($self->any())
                    ->method('getMetadataFor')
                    ->will($self->returnCallback(function() use ($self) {
                        $classMetadata = $self->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                            ->disableOriginalConstructor()
                            ->getMock();

                        $classMetadata->expects($self->any())
                            ->method('getIdentifierFieldNames')
                            ->will($self->returnValue(array('id', 'name')));

                        return $classMetadata;
                    }));

                $em = new EntityManagerMock();
                $em->_setMetadataFactory($metadataFactory);

                return $em;
            }));

        $strategy = new EntityIndexingStrategy($registry);
        foreach (array('_', '|') as $separator) {
            $index = '1'.$separator.'Foo';
            $strategy->setSeparator($separator);
            $this->assertSame(array('id' => '1', 'name' => 'Foo' ), $strategy->revertIndex($index, 'Entity'));
        }
    }

    public function testGetIndexForNonEntities()
    {
        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $strategy = new EntityIndexingStrategy($registry);
        $this->assertSame(null, $strategy->getIndex(new \stdClass(), $this->dataMapper));
    }
}
