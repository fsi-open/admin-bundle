<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Tests\Extension\Core;

use FSi\Component\DataSource\DataSourceFactory;
use FSi\Component\DataSource\Driver\Collection\CollectionFactory;
use FSi\Component\DataSource\Driver\Collection\Extension\Core\CoreExtension;
use FSi\Component\DataSource\Driver\DriverFactoryManager;
use FSi\Component\DataSource\Extension\Core\Pagination\PaginationExtension;
use FSi\Component\DataSource\Event\DataSourceEvent;

/**
 * Tests for Pagination Extension.
 */
class PaginationExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * First case of event (when page is not 1).
     */
    public function testPaginationExtension()
    {
        $self = $this;

        $cases = array(
            array(
                'first_result' => 20,
                'max_results' => 20,
                'page' => 2,
                'current_page' => 2
            ),
            array(
                'first_result' => 20,
                'max_results' => 0,
                'current_page' => 1
            ),
            array(
                'first_result' => 0,
                'max_results' => 20,
                'current_page' => 1
            ),
        );

        $driver = $this->getMock('FSi\Component\DataSource\Driver\DriverInterface');
        $extension = new PaginationExtension();

        foreach ($cases as $case) {
            $datasource = $this->getMock('FSi\Component\DataSource\DataSource', array(), array($driver));

            $datasource
                ->expects($this->any())
                ->method('getName')
                ->will($this->returnValue('datasource'))
            ;

            $datasource
                ->expects($this->any())
                ->method('getMaxResults')
                ->will($this->returnValue($case['max_results']))
            ;

            $datasource
                ->expects($this->any())
                ->method('getFirstResult')
                ->will($this->returnValue($case['first_result']))
            ;

            $subscribers = $extension->loadSubscribers();
            $subscriber = array_shift($subscribers);
            $event = new DataSourceEvent\ParametersEventArgs($datasource, array());
            $subscriber->postGetParameters($event);

            if (isset($case['page'])) {
                $this->assertSame(
                    array(
                        'datasource' => array(
                            PaginationExtension::PARAMETER_MAX_RESULTS => 20,
                            PaginationExtension::PARAMETER_PAGE => 2
                        )
                    ),
                    $event->getParameters()
                );
            } else {
                $parameters = $event->getParameters();
                if (isset($parameters['datasource']))
                    $this->assertArrayNotHasKey(PaginationExtension::PARAMETER_PAGE, $parameters['datasource']);
            }

            $view = $this->getMock('FSi\Component\DataSource\DataSourceViewInterface');
            $view
                ->expects($this->any())
                ->method('setAttribute')
                ->will($this->returnCallback(function($attribute, $value) use ($self, $case) {
                    switch ($attribute) {
                        case 'page':
                            $self->assertEquals($case['current_page'], $value);
                            break;
                    };
                }))
            ;

            $subscriber->postBuildView(new DataSourceEvent\ViewEventArgs($datasource, $view));
        }
    }

    public function testSetMaxResultsByBindRequest()
    {
        $extensions = array(
            new PaginationExtension()
        );
        $driverExtensions = array(new CoreExtension());
        $driverFactory = new CollectionFactory($driverExtensions);
        $driverFactoryManager = new DriverFactoryManager(array($driverFactory));
        $factory = new DataSourceFactory($driverFactoryManager, $extensions);
        $dataSource = $factory->createDataSource('collection',  array(), 'foo_source');

        $dataSource->bindParameters(array(
            'foo_source' => array(
                PaginationExtension::PARAMETER_MAX_RESULTS => 105
            )
        ));

        $this->assertEquals(105, $dataSource->getMaxResults());
    }
}
