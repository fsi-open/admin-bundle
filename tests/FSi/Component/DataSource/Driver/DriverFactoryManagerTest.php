<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Driver;

use FSi\Component\DataSource\Driver\Doctrine\Extension\Core\Field;
use FSi\Component\DataSource\Driver\DriverFactoryManager;

/**
 * Basic tests for Doctrine driver.
 */
class DriverFactoryManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testBasicManagerOperations()
    {
        $doctrineFactory = $this->getMockBuilder('FSi\Component\DataSource\Driver\Doctrine\DoctrineFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrineFactory->expects($this->any())
            ->method('getDriverType')
            ->will($this->returnValue('doctrine'));

        $collectionFactory = $this->getMockBuilder('FSi\Component\DataSource\Driver\Collection\CollectionFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $collectionFactory->expects($this->any())
            ->method('getDriverType')
            ->will($this->returnValue('collection'));


        $manager = new DriverFactoryManager(array(
            $doctrineFactory,
            $collectionFactory
        ));

        $this->assertTrue($manager->hasFactory('doctrine'));
        $this->assertTrue($manager->hasFactory('collection'));

        $this->assertSame($doctrineFactory, $manager->getFactory('doctrine'));
        $this->assertSame($collectionFactory, $manager->getFactory('collection'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidFactory()
    {
        $notFactory = new \DateTime();

        $manager = new DriverFactoryManager(array(
            $notFactory,
        ));
    }
}
