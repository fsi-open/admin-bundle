<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\Tests\DataSource\Extension\Configuration\EventSubscriber;

use FSi\Bundle\DataSourceBundle\DataSource\Extension\Configuration\EventSubscriber\ConfigurationBuilder;
use FSi\Component\DataSource\Event\DataSourceEvent\ParametersEventArgs;
use FSi\Component\DataSource\Event\DataSourceEvents;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;

class ConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var ConfigurationBuilder
     */
    protected $subscriber;

    public function setUp()
    {
        $kernelMockBuilder = $this->getMockBuilder('Symfony\Component\HttpKernel\Kernel')
            ->setConstructorArgs(array('dev', true));
        if (version_compare(Kernel::VERSION, '2.7.0', '<')) {
            $kernelMockBuilder->setMethods(array('registerContainerConfiguration', 'registerBundles', 'getBundles', 'init'));
        } else {
            $kernelMockBuilder->setMethods(array('registerContainerConfiguration', 'registerBundles', 'getBundles'));
        }
        $this->kernel = $kernelMockBuilder->getMock();

        $this->subscriber = new ConfigurationBuilder($this->kernel);
    }

    public function testSubscribedEvents()
    {
        $this->assertEquals(
            $this->subscriber->getSubscribedEvents(),
            array(DataSourceEvents::PRE_BIND_PARAMETERS => array('readConfiguration', 1024))
        );
    }

    public function testReadConfigurationFromOneBundle()
    {
        $self = $this;
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnCallback(function() use ($self) {
                $bundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', array('getPath'));
                $bundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/FooBundle'));

                return array($bundle);
            }));

        $dataSource = $this->getMockBuilder('FSi\Component\DataSource\DataSource')
            ->disableOriginalConstructor()
            ->getMock();

        $dataSource->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('news'));

        $dataSource->expects($this->once())
            ->method('addField')
            ->with('title', 'text', 'like', array('label' => 'Title'));

        $event = new ParametersEventArgs($dataSource, array());

        $this->subscriber->readConfiguration($event);
    }

    public function testReadConfigurationFromManyBundles()
    {
        $self = $this;
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnCallback(function() use ($self) {
                $fooBundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', array('getPath'));
                $fooBundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/FooBundle'));

                $barBundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', array('getPath'));
                $barBundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/BarBundle'));
                return array(
                    $fooBundle,
                    $barBundle
                );
            }));

        $dataSource = $this->getMockBuilder('FSi\Component\DataSource\DataSource')
            ->disableOriginalConstructor()
            ->getMock();

        $dataSource->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('news'));

        // 0 - 3 getName() is called
        $dataSource->expects($this->at(4))
            ->method('addField')
            ->with('title', 'text', 'like', array('label' => 'News Title'));

        $dataSource->expects($this->at(5))
            ->method('addField')
            ->with('author', null, null, array());


        $event = new ParametersEventArgs($dataSource, array());

        $this->subscriber->readConfiguration($event);
    }
}