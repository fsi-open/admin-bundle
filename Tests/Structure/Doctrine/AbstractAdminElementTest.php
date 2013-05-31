<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Structure\Doctrine;

use FSi\Bundle\AdminBundle\Structure\Doctrine\AbstractAdminElement as DoctrineAbstractAdminElement;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AbstractAdminElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineAbstractAdminElement
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
     * @@expectedException \FSi\Bundle\AdminBundle\Exception\RuntimeException
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
}

class FooDoctrineElement extends DoctrineAbstractAdminElement
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
}

class FooEntity
{

}