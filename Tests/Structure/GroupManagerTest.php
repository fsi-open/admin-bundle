<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Structure;

use FSi\Bundle\AdminBundle\Structure\Group;
use FSi\Bundle\AdminBundle\Structure\GroupManager;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class GroupManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testAddGroup()
    {
        $group = new Group('admin.main.group');
        $group->setId('admin.main.group.id');

        $element = $this->getMock('FSi\Bundle\AdminBundle\Structure\ElementInterface');
        $element->expects($this->any())
            ->method('getId')
            ->will($this->returnValue('admin.fake.element'));

        $group->addElement($element);

        $groupManager = new GroupManager();
        $groupManager->addGroup($group);
        $this->assertTrue($groupManager->hasGroup('admin.main.group.id'));
        $this->assertSame($element, $groupManager->findElementById('admin.fake.element'));
    }
}