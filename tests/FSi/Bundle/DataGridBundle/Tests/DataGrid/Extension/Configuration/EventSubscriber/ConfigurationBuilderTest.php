<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Tests\DataGrid\Extension\Configuration\EventSubscriber;

use FSi\Component\DataGrid\DataGridEvent;
use FSi\Component\DataGrid\DataGridEvents;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelInterface;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Configuration\EventSubscriber\ConfigurationBuilder;

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
            array(DataGridEvents::PRE_SET_DATA => array('readConfiguration', 128))
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

        $dataGrid = $this->getMockBuilder('FSi\Component\DataGrid\DataGrid')
            ->disableOriginalConstructor()
            ->getMock();

        $dataGrid->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('news'));

        $dataGrid->expects($this->once())
            ->method('addColumn')
            ->with('id', 'number', array('label' => 'Identity'));

        $event = new DataGridEvent($dataGrid, array());

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

        $dataGrid = $this->getMockBuilder('FSi\Component\DataGrid\DataGrid')
            ->disableOriginalConstructor()
            ->getMock();

        $dataGrid->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('news'));

        // 0 - 3 getName() is called
        $dataGrid->expects($this->at(4))
            ->method('addColumn')
            ->with('id', 'number', array('label' => 'ID'));

        $dataGrid->expects($this->at(5))
            ->method('addColumn')
            ->with('title', 'text', array());

        $dataGrid->expects($this->at(6))
            ->method('addColumn')
            ->with('author', 'text', array());

        $event = new DataGridEvent($dataGrid, array());

        $this->subscriber->readConfiguration($event);
    }
}