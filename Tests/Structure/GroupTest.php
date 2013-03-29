<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Structure;

use FSi\Bundle\AdminBundle\Structure\Group;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class GroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Bundle\AdminBundle\Structure\Group
     */
    protected $group;

    public function setUp()
    {
        $this->group = new Group('admin.main.group');
    }

    public function tearDown()
    {
        unset($this->group);
    }

    public function testAddElement()
    {
        $element = $this->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $element->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('admin.fake.element'));

        $this->group->addElement($element);

        $this->assertTrue($this->group->hasElement('admin.fake.element'));
        $this->assertSame($element, $this->group->getElement('admin.fake.element'));
        $this->assertSame(1, count($this->group->getElements()));
        $this->assertSame(null, $this->group->getElement('admin.element.that.not.exists'));
    }

    public function testAddElements()
    {
        $element1 = $this->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $element1->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('admin.fake.element1'));

        $element2 = $this->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $element2->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('admin.fake.element2'));

        $this->group->setElements(array(
            $element1,
            $element2
        ));

        $this->assertTrue($this->group->hasElement('admin.fake.element1'));
        $this->assertSame($element1, $this->group->getElement('admin.fake.element1'));
        $this->assertTrue($this->group->hasElement('admin.fake.element2'));
        $this->assertSame($element2, $this->group->getElement('admin.fake.element2'));
        $this->assertSame(2, count($this->group->getElements()));
    }

    /**
     * @expectedException FSi\Bundle\AdminBundle\Exception\InvalidArgumentException
     */
    public function testAddTwiceSameElement()
    {
        $element = $this->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $element->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('admin.fake.element'));

        $this->group->addElement($element);
        $this->group->addElement($element);
    }
}