<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Tests\DataGrid\Extension\Symfony\ColumnType;

use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Bundle\DataGridBundle\Tests\Fixtures\Request;
use FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension\DefaultColumnOptionsExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class ActionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @var Action
     */
    private $column;

    protected function setUp()
    {
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->requestStack = $this->getMock('Symfony\Component\HttpFoundation\RequestStack');
        $this->requestStack->expects($this->any())
            ->method('getMasterRequest')
            ->will($this->returnValue(new Request()));

        $column = new Action($this->router, $this->requestStack);
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
        $this->router->expects($this->any())
            ->method('generate')
            ->with('foo', array('redirect_uri' => Request::RELATIVE_URI), false)
            ->will($this->returnValue('/test/bar?redirect_uri=' . urlencode(Request::ABSOLUTE_URI)));

        $this->column->setName('action');
        $this->column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($this->column);


        $this->column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'absolute' => false
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'content' => 'edit',
                   'field_mapping_values' => array(
                           'foo' => 'bar'
                   ),
                   'url_attr' => Array (
                       'href' => '/test/bar?redirect_uri=http%3A%2F%2Fexample.com%2F%3Ftest%3D1%26test%3D2'
                   )
               )
           ),
           $this->column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }

    public function testFilterValueAvailableActionInActionsOption()
    {
        $this->router->expects($this->once())
            ->method('generate')
            ->with('foo', array('foo' => 'bar', 'redirect_uri' => Request::RELATIVE_URI), true)
            ->will($this->returnValue('https://fsi.pl/test/bar?redirect_uri=' . urlencode(Request::RELATIVE_URI)));

        $this->column->setName('action');
        $this->column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($this->column);

        $this->column->setOption('field_mapping', array('foo'));
        $this->column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'parameters_field_mapping' => array('foo' => 'foo'),
                'absolute' => true
            )
        ));

       $this->assertSame(
           array(
               'edit' => array(
                   'content' => 'edit',
                   'field_mapping_values' => array(
                           'foo' => 'bar'
                   ),
                   'url_attr' => array (
                       'href' => 'https://fsi.pl/test/bar?redirect_uri=' . urlencode(Request::RELATIVE_URI)
                   )
               )
           ),
           $this->column->filterValue(array(
               'foo' => 'bar'
           ))
       );
    }


    public function testFilterValueWithRedirectUriFalse()
    {
        $this->router->expects($this->once())
            ->method('generate')
            ->with('foo', array(), false)
            ->will($this->returnValue('/test/bar'));

        $this->column->setName('action');
        $this->column->initOptions();

        $extension = new DefaultColumnOptionsExtension();
        $extension->initOptions($this->column);

        $this->column->setOption('actions', array(
            'edit' => array(
                'route_name' => 'foo',
                'absolute' => false,
                'redirect_uri' => false
            )
        ));

        $this->assertSame(
            array(
                'edit' => array(
                    'content' => 'edit',
                    'field_mapping_values' => array(
                        'foo' => 'bar'
                    ),
                    'url_attr' => array (
                        'href' => '/test/bar'
                    )
                )
            ),
            $this->column->filterValue(array(
                'foo' => 'bar'
            ))
        );
    }
}
