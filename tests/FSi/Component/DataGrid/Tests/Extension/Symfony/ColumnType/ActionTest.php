<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Tests\Extension\Symfony\ColumnType;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use FSi\Component\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    private $container;
    private $column;

    protected function setUp()
    {
        if (!interface_exists('Symfony\Component\DependencyInjection\ContainerInterface')
            || !interface_exists('Symfony\Component\Routing\RouterInterface')
            || !class_exists('Symfony\Component\HttpFoundation\Request')) {
            $this->markTestSkipped('Symfony Column Action require Symfony\Component\DependencyInjection\ContainerInterface interface.');
        }

        $this->container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $this->column = $column;
    }

    /**
     * @expectedException \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    public function testFilterValueWrongActionsOptionType()
    {
        $this->column->setOption('actions', 'boo');
        $this->column->filterValue(array());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testFilterValueInvalidActionInActionsOption()
    {
        $this->column->setOption('actions', array('edit' => 'asdasd'));
        $this->column->filterValue(array());
    }

    public function testFilterValueRequiredActionInActionsOption()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {

                if ($serviceId == 'router') {
                    $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                    $router->expects($self->once())
                        ->method('generate')
                        ->with('foo', array('redirect_uri' => MyRequest::RELATIVE_URI), false)
                        ->will($self->returnValue('/test/bar?redirect_uri=' . urlencode(MyRequest::ABSOLUTE_URI)));

                    return $router;
                }

                if ($serviceId == 'request') {
                    return new MyRequest();
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);


        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'absolute' => false
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'url' => '/test/bar?redirect_uri=' . urlencode(MyRequest::ABSOLUTE_URI),
                   'content' => 'edit',
                   'field_mapping_values' => array(
                           'foo' => 'bar'
                   ),
                   'url_attr' => Array (
                       'href' => '/test/bar?redirect_uri=http%3A%2F%2Fexample.com%2F%3Ftest%3D1%26test%3D2'
                   )
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {
                switch ($serviceId) {
                    case 'router':
                        $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                        $router->expects($self->once())
                            ->method('generate')
                            ->with('foo', array('foo' => 'bar', 'redirect_uri' => MyRequest::RELATIVE_URI), true)
                            ->will($self->returnValue('https://fsi.pl/test/bar?redirect_uri=' . urlencode(MyRequest::RELATIVE_URI)));
                        return $router;
                        break;
                    case 'request':
                        return new MyRequest();
                        break;
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $column->setOption('field_mapping', array('foo'));
        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'parameters_field_mapping' => array('foo' => 'foo'),
                'absolute' => true
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'url' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode(MyRequest::RELATIVE_URI),
                   'content' => 'edit',
                   'field_mapping_values' => array(
                           'foo' => 'bar'
                   ),
                   'url_attr' => array (
                       'href' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode(MyRequest::RELATIVE_URI)
                   )
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }


    public function testFilterValueWithRedirectUriFalse()
    {
        $self = $this;

        $this->container->expects($this->any())
            ->method('get')
            ->will($this->returnCallback(function($serviceId) use ($self) {
                switch ($serviceId) {
                    case 'router':
                        $router = $self->getMock('Symfony\Component\Routing\RouterInterface');
                        $router->expects($self->once())
                            ->method('generate')
                            ->with('foo', array(), false)
                            ->will($self->returnValue('/test/bar'));

                        return $router;
                    break;
                    case 'request':
                        return new MyRequest();
                    break;
                }
            }));

        $column = new Action($this->container);
        $column->setName('action');
        $column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($column);

        $column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'absolute' => false,
                'redirect_uri' => false
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'url' => '/test/bar',
                   'content' => 'edit',
                   'field_mapping_values' => array(
                       'foo' => 'bar'
                   ),
                   'url_attr' => array (
                       'href' => '/test/bar'
                   )
               )
           ),
           $column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }
}

class MyRequest extends Request
{
    const ABSOLUTE_URI = 'http://example.com/?test=1&test=2';
    const RELATIVE_URI = '/?test=1&test=2';

    public function __construct()
    {
    }

    public function getUri()
    {
        return self::ABSOLUTE_URI;
    }

    public function getRequestUri()
    {
        return self::RELATIVE_URI;
    }
}
