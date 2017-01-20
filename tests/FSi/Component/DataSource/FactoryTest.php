<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests;

use FSi\Component\DataSource\DataSourceFactory;
use FSi\Component\DataSource\Driver\Collection\CollectionFactory;
use FSi\Component\DataSource\Driver\DriverFactoryManager;

/**
 * Tests for DataSourceFactory.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Checks proper extensions loading.
     */
    public function testExtensionsLoading()
    {
        $extension1 = $this->getMock('FSi\Component\DataSource\DataSourceExtensionInterface');
        $extension2 = $this->getMock('FSi\Component\DataSource\DataSourceExtensionInterface');

        $extension1
            ->expects($this->any())
            ->method('loadDriverExtensions')
            ->will($this->returnValue(array()))
        ;

        $extension2
            ->expects($this->any())
            ->method('loadDriverExtensions')
            ->will($this->returnValue(array()))
        ;

        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));

        $extensions = array($extension1, $extension2);

        $factory = new DataSourceFactory($driveFactoryManager, $extensions);
        $datasource = $factory->createDataSource('collection', array('collection' => array()));

        $factoryExtensions = $factory->getExtensions();
        $datasourceExtensions = $datasource->getExtensions();

        $this->assertEquals(count($factoryExtensions), count($extensions));
        $this->assertEquals(count($datasourceExtensions), count($extensions));
    }

    /**
     * Checks exception thrown when loading inproper extensions.
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function testFactoryException2()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $datasourceFactory = new DataSourceFactory($driveFactoryManager, array(new \stdClass()));
    }

    /**
     * Checks exception thrown when loading scalars in place of extensions.
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function testFactoryException3()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $datasourceFactory = new DataSourceFactory($driveFactoryManager, array('scalar'));
    }

    /**
     * Checks exception thrown when creating DataSource with non-existing driver
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     * @expectedExceptionMessage Driver "unknownDriver" doesn't exist.
     */
    public function testFactoryException6()
    {
        $factory = new DataSourceFactory(new DriverFactoryManager());
        $factory->createDataSource('unknownDriver');
    }

    /**
     * Checks exception thrown when creating DataSource with non unique name.
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function testFactoryCreateDataSourceException1()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $factory = new DataSourceFactory($driveFactoryManager);

        $factory->createDataSource('collection', array('collection' => array()), 'unique');
        $factory->createDataSource('collection', array('collection' => array()), 'nonunique');
        $factory->createDataSource('collection', array('collection' => array()), 'nonunique');
    }

    /**
     * Checks exception thrown when creating DataSource with wrong name.
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function testFactoryCreateDataSourceException2()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $factory = new DataSourceFactory($driveFactoryManager);
        $factory->createDataSource('collection', array('collection' => array()), 'wrong-one');
    }

    /**
     * Checks exception thrown when creating DataSource with empty name.
     *
     * @expectedException \FSi\Component\DataSource\Exception\DataSourceException
     */
    public function testFactoryCreateDataSourceException3()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $factory = new DataSourceFactory($driveFactoryManager);
        $factory->createDataSource('collection', array('collection' => array()), '');
    }

    /**
     * Checks adding DataSoucre to factory.
     */
    public function testAddDataSource()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $factory = new DataSourceFactory($driveFactoryManager);

        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $datasource2 = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $datasource->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name'));

        $datasource2->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name'));

        $datasource->expects($this->atLeastOnce())
            ->method('setFactory')
            ->with($factory);

        $factory->addDataSource($datasource);
        //Check if adding it twice won't cause exception.
        $factory->addDataSource($datasource);

        //Checking exception for adding different datasource with the same name.
        $this->setExpectedException('FSi\Component\DataSource\Exception\DataSourceException');
        $factory->addDataSource($datasource2);
    }

    /**
     * Checks fetching parameters of all and others datasources.
     */
    public function testGetAllAndOtherParameters()
    {
        $driveFactoryManager = new DriverFactoryManager(array(
            new CollectionFactory()
        ));
        $factory = new DataSourceFactory($driveFactoryManager);

        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource1 = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $datasource2 = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

        $params1 = array(
            'key1' => 'value1',
        );

        $params2 = array(
            'key2' => 'value2',
        );

        $result = array_merge($params1, $params2);

        $datasource1->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name'));

        $datasource2->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('name2'));

        $datasource1->expects($this->any())
            ->method('getParameters')
            ->will($this->returnValue($params1));

        $datasource2->expects($this->any())
            ->method('getParameters')
            ->will($this->returnValue($params2));

        $factory->addDataSource($datasource1);
        $factory->addDataSource($datasource2);

        $this->assertEquals($factory->getOtherParameters($datasource1), $params2);
        $this->assertEquals($factory->getOtherParameters($datasource2), $params1);
        $this->assertEquals($factory->getAllParameters(), $result);
    }
}
