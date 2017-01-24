<?php

declare(strict_types=1);

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


        $manager = new DriverFactoryManager([
            $doctrineFactory,
            $collectionFactory
        ]);

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

        $manager = new DriverFactoryManager([
            $notFactory,
        ]);
    }
}
