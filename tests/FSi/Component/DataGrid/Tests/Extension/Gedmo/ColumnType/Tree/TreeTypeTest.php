<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Gedmo\ColumnType;

use FSi\Component\DataGrid\Tests\Fixtures\EntityTree;
use FSi\Component\DataGrid\Tests\Fixtures\EntityManagerMock;
use FSi\Component\DataGrid\Tests\Fixtures\EventManagerMock;
use FSi\Component\DataGrid\Extension\Gedmo\ColumnType\Tree;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class TreeTypeTest extends \PHPUnit_Framework_TestCase
{
    public function testWrongValue()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ManagerRegistry')) {
            $this->markTestSkipped('Doctrine\Common\Persistence\ManagerRegistry is required for testGetValue in gedmo.tree column type');
        }

        $registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
        $column = new Tree($registry);
        $column->setName('tree');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $object = 'This is string, not object';

        $this->setExpectedException('InvalidArgumentException');
        $column->getValue($object);
    }

    public function testGetValue()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ManagerRegistry')
            || !class_exists('Gedmo\Tree\TreeListener')) {
            $this->markTestSkipped('Doctrine\Common\Persistence\ManagerRegistry is required for testGetValue in gedmo.tree column type');
        }

        $dataGrid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $registry = $this->getManagerRegistry();
        $dataMapper = $this->getMock('FSi\Component\DataGrid\DataMapper\DataMapperInterface');

        $dataMapper->expects($this->any())
            ->method('getData')
            ->will($this->returnValue(new EntityTree("foo")));

        $column = new Tree($registry);
        $column->setName('tree');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $column->setDataMapper($dataMapper);
        $column->setOption('field_mapping', array('foo'));
        $column->setDataGrid($dataGrid);
        $object = new EntityTree("foo");

        $column->getValue($object);

        $this->assertSame(
            array(
                "id" => "foo",
                "root" => "root",
                "left" => "left",
                "right" => "right",
                "level" => "level",
                "children" => 2,
                "parent" => "bar",
            ),
            $column->getViewAttributes()
        );
    }


    protected function getManagerRegistry()
    {
        $self = $this;

        $managerRegistry = $this->getMock("Doctrine\\Common\\Persistence\\ManagerRegistry");
        $managerRegistry->expects($this->any())
            ->method('getManagerForClass')
            ->will($this->returnCallback(function() use ($self) {
                $manager = $self->getMock("Doctrine\\Common\\Persistence\\ObjectManager");
                $manager->expects($self->any())
                    ->method('getMetadataFactory')
                    ->will($self->returnCallback(function() use ($self) {
                        $metadataFactory = $self->getMock("Doctrine\\Common\\Persistence\\Mapping\\ClassMetadataFactory");

                        $metadataFactory->expects($self->any())
                            ->method('getMetadataFor')
                            ->will($self->returnCallback(function($class) use ($self) {
                                switch ($class) {
                                    case "FSi\\Component\\DataGrid\\Tests\\Fixtures\\EntityTree" :
                                        $metadata = $self->getMock('Doctrine\\ORM\\Mapping\\ClassMetadataInfo', array(), array($class));
                                        $metadata->expects($self->any())
                                            ->method('getIdentifierFieldNames')
                                            ->will($self->returnValue(array(
                                                'id'
                                            )));
                                        break;
                                }

                                return $metadata;
                            }));

                        $metadataFactory->expects($self->any())
                            ->method('getClassMetadata')
                            ->will($self->returnCallback(function($class) {
                                return $metadataFactory->getMetadataFor($class);
                            }));

                        return $metadataFactory;
                    }));

                $manager->expects($self->any())
                    ->method('getClassMetadata')
                    ->will($self->returnCallback(function($class) use ($self) {
                        switch ($class) {
                            case "FSi\\Component\\DataGrid\\Tests\\Fixtures\\EntityTree" :
                                $metadata = $self->getMock('Doctrine\\ORM\\Mapping\\ClassMetadataInfo', array(), array($class));
                                $metadata->expects($self->any())
                                    ->method('getIdentifierFieldNames')
                                    ->will($self->returnValue(array(
                                        'id'
                                    )));
                                $metadata->isMappedSuperclass = false;
                                $metadata->rootEntityName = $class;
                                break;
                        }

                        return $metadata;
                    }));

                return $manager;
            }));

        $treeListener = $this->getMock('Gedmo\Tree\TreeListener');
        $strategy = $this->getMock('Gedmo\Tree\Strategy');

        $treeListener->expects($this->once())
            ->method('getStrategy')
            ->will($this->returnValue($strategy));

        $treeListener->expects($this->once())
            ->method('getConfiguration')
            ->will($this->returnValue(
                array(
                    'left' => 'left',
                    'right' => 'right',
                    'root' => 'root',
                    'level' => 'level',
                    'parent' => 'parent'
                )
            ));

        $strategy->expects($this->once())
            ->method('getName')
            ->will($this->returnValue('nested'));

        $evm = new EventManagerMock(array($treeListener));
        $em = new EntityManagerMock();
        $em->_setEventManager($evm);

        $managerRegistry->expects($this->any())
            ->method('getManager')
            ->will($this->returnValue($em));

        return $managerRegistry;
    }
}
