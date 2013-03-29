<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Structure;

use FSi\Bundle\AdminBundle\Structure\AbstractDoctrineAdminElement;
use FSi\Component\DataGrid\DataGridInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AbstractDoctrineAdminElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AbstractDoctrineAdminElement
     */
    protected $element;

    /**
     * @var \Doctrine\Common\Persistence\ManagerRegistry
     */
    protected $registry;

    public function setUp()
    {
        $self = $this;

        $this->element = new FooDoctrineElement();
        $this->registry = $this->getMock('Doctrine\Common\Persistence\ManagerRegistry');
    }

    public function tearDown()
    {
        unset($this->element, $this->registry);
    }

    /**
     * @@expectedException FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function testHasObjectManagerWithoutManager()
    {
        $this->element->getObjectManager();
    }

    public function testSave()
    {
        $self = $this;
        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with('FooEntity')
            ->will($this->returnCallback(function() use ($self){
                $om = $self->getMock('Doctrine\Common\Persistence\ObjectManager');

                $om->expects($this->at(0))
                    ->method('persist');
                $om->expects($this->at(1))
                    ->method('flush');

                return $om;
            }));

        $this->element->setManagerRegistry($this->registry);
        $this->element->save(new FooEntity());
    }

    public function testDelete()
    {
        $self = $this;
        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with('FooEntity')
            ->will($this->returnCallback(function() use ($self){
                $om = $self->getMock('Doctrine\Common\Persistence\ObjectManager');

                $om->expects($this->at(0))
                    ->method('remove');
                $om->expects($this->at(1))
                    ->method('flush');

                return $om;
            }));

        $this->element->setManagerRegistry($this->registry);
        $this->element->delete(new FooEntity());
    }

    public function testGetDataGridColumnActionOptions()
    {
        $self = $this;
        $this->registry
            ->expects($this->any())
            ->method('getManagerForClass')
            ->with('FooEntity')
            ->will($this->returnCallback(function() use ($self){
                $om = $self->getMock('Doctrine\Common\Persistence\ObjectManager');

                $om->expects($this->at(0))
                    ->method('getClassMetadata')
                    ->with('FooEntity')
                    ->will($this->returnCallback(function() use($self) {
                        $metadata = $self->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
                        $metadata->expects($this->at(0))
                            ->method('getIdentifierFieldNames')
                            ->will($self->returnValue(array('id')));

                        return $metadata;
                    }));

                return $om;
            }));

        $this->element->setManagerRegistry($this->registry);
        $datagrid = $this->getMock('FSi\Component\DataGrid\DataGridInterface');
        $datagrid->expects($this->at(0))
            ->method('hasColumnType')
            ->with('gedmo_tree')
            ->will($this->returnValue(true));

        $options = $this->element->getOptionsGridAction($datagrid);

        $this->assertSame($options['field_mapping'], array('id'));
        $this->assertSame($options['translation_domain'], 'FSiAdminBundle');
        $this->assertTrue(array_key_exists('edit', $options['actions']));
        $this->assertTrue(array_key_exists('delete', $options['actions']));
        $this->assertTrue(array_key_exists('moveup', $options['actions']));
        $this->assertTrue(array_key_exists('movedown', $options['actions']));
    }
}

class FooDoctrineElement extends AbstractDoctrineAdminElement
{
    public function getId()
    {
        return 'foo.admin.doctrine.element';
    }

    public function getName()
    {
        return 'foo.admin.doctrine.element.name';
    }

    public function getClassName()
    {
        return 'FooEntity';
    }

    public function hasEditForm($data = null)
    {
        return true;
    }

    public function getOptionsGridAction(DataGridInterface $datagrid)
    {
        return $this->getDataGridActionColumnOptions($datagrid);
    }
}

class FooEntity
{

}