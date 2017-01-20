<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Extension\Symfony;

use FSi\Component\DataSource\Extension\Symfony\Core\CoreExtension;
use Symfony\Component\HttpFoundation\Request;
use FSi\Component\DataSource\Event\DataSourceEvent;

/**
 * Tests for Symfony Core Extension.
 */
class CoreExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        if (!class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony HttpFoundation needed!');
        }
    }

    /**
     * Checks if Request if converted correctly.
     */
    public function testBindParameters()
    {
        $extension = new CoreExtension();
        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));
        $data1 = array('key1' => 'value1', 'key2' => 'value2');
        $data2 = $data1;

        $subscribers = $extension->loadSubscribers();
        $subscriber = array_shift($subscribers);

        $args = new DataSourceEvent\ParametersEventArgs($datasource, $data2);
        $subscriber->preBindParameters($args);
        $data2 = $args->getParameters();
        $this->assertEquals($data1, $data2);

        $request = new Request($data2);
        $args = new DataSourceEvent\ParametersEventArgs($datasource, $request);
        $subscriber->preBindParameters($args);
        $request = $args->getParameters();
        $this->assertTrue(is_array($request));
        $this->assertEquals($data1, $request);
    }
}
