<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundleBundle\Tests\DataSource\Extension\Configuration\EventSubscriber;

use AdminPanel\Symfony\AdminBundle\DataSource\Extension\Configuration\EventSubscriber\ConfigurationBuilder;
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
            ->setConstructorArgs(['dev', true]);
        if (version_compare(Kernel::VERSION, '2.7.0', '<')) {
            $kernelMockBuilder->setMethods(['registerContainerConfiguration', 'registerBundles', 'getBundles', 'init']);
        } else {
            $kernelMockBuilder->setMethods(['registerContainerConfiguration', 'registerBundles', 'getBundles']);
        }
        $this->kernel = $kernelMockBuilder->getMock();

        $this->subscriber = new ConfigurationBuilder($this->kernel);
    }

    public function testSubscribedEvents()
    {
        $this->assertEquals(
            $this->subscriber->getSubscribedEvents(),
            [DataSourceEvents::PRE_BIND_PARAMETERS => ['readConfiguration', 1024]]
        );
    }

    public function testReadConfigurationFromOneBundle()
    {
        $self = $this;
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnCallback(function () use ($self) {
                $bundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', ['getPath']);
                $bundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/FooBundle'));

                return [$bundle];
            }));

        $dataSource = $this->getMockBuilder('FSi\Component\DataSource\DataSource')
            ->disableOriginalConstructor()
            ->getMock();

        $dataSource->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('news'));

        $dataSource->expects($this->once())
            ->method('addField')
            ->with('title', 'text', 'like', ['label' => 'Title']);

        $event = new ParametersEventArgs($dataSource, []);

        $this->subscriber->readConfiguration($event);
    }

    public function testReadConfigurationFromManyBundles()
    {
        $self = $this;
        $this->kernel->expects($this->once())
            ->method('getBundles')
            ->will($this->returnCallback(function () use ($self) {
                $fooBundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', ['getPath']);
                $fooBundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/FooBundle'));

                $barBundle = $self->getMock('Symfony\Component\HttpKernel\Bundle\Bundle', ['getPath']);
                $barBundle->expects($self->any())
                    ->method('getPath')
                    ->will($self->returnValue(__DIR__ . '/../../../../Fixtures/BarBundle'));
                return [
                    $fooBundle,
                    $barBundle
                ];
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
            ->with('title', 'text', 'like', ['label' => 'News Title']);

        $dataSource->expects($this->at(5))
            ->method('addField')
            ->with('author', null, null, []);


        $event = new ParametersEventArgs($dataSource, []);

        $this->subscriber->readConfiguration($event);
    }
}
