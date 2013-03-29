<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Tests\Structure;

use FSi\Bundle\AdminBundle\Structure\AbstractAdminElement;
use FSi\Component\DataGrid\DataGridInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class AbstractAdminElementTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \FSi\Bundle\AdminBundle\Structure\AbstractAdminElement
     */
    protected $element;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    protected $datasourceFactory;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    public function setUp()
    {
        $this->element = new FooElement();
        $this->datagridFactory = $this->getMock('FSi\Component\DataGrid\DataGridFactoryInterface');
        $this->datasourceFactory = $this->getMock('FSi\Component\DataSource\DataSourceFactoryInterface');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
    }

    public function tearDown()
    {
        unset($this->element);
    }

    /**
     * @expectedException FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function testGetDataGridFactoryWithoutDataGridFactory()
    {
        $this->element->getDataGridFactory();
    }

    /**
     * @expectedException FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function testGetDataSourceFactoryWithoutDataSourceFactory()
    {
        $this->element->getDataSourceFactory();
    }

    /**
     * @expectedException FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function testGetFormFactoryWithoutFormFactory()
    {
        $this->element->getFormFactory();
    }

    public function testGetDataGrid()
    {
        $self = $this;

        $this->datagridFactory
            ->expects($this->once()) //its important to test how many times datagrid is created
            ->method('createDataGrid')
            ->with('foo_datagrid')
            ->will($this->returnCallback(function() use ($self){
                return $self->getMock('FSi\Component\DataGrid\DataGridInterface');
            }));

        $this->element->setDataGridFactory($this->datagridFactory);
        $this->assertTrue($this->element->hasDataGrid());

        $datagrid = $this->element->getDataGrid();
        $this->assertInstanceOf('FSi\Component\DataGrid\DataGridInterface', $datagrid);
    }

    public function testGetDataSource()
    {
        $self = $this;

        $this->datasourceFactory
            ->expects($this->once()) //its important to test how many times datasource is created
            ->method('createDataSource')
            ->with('doctrine', array(), 'foo_datasource')
            ->will($this->returnCallback(function() use ($self){
                return $self->getMock('FSi\Component\DataSource\DataSourceInterface');
            }));

        $this->element->setDataSourceFactory($this->datasourceFactory);
        $this->assertTrue($this->element->hasDataSource());

        $datasource = $this->element->getDataSource();
        $this->assertInstanceOf('FSi\Component\DataSource\DataSourceInterface', $datasource);
    }

    public function testGetForm()
    {
        $self = $this;

        $this->formFactory
            ->expects($this->once()) //its important to test how many times datasource is created
            ->method('createBuilder')
            ->with('foo_form', null)
            ->will($this->returnCallback(function() use ($self){
                return $self->getMock('Symfony\Component\Form\FormFactoryInterface');
            }));

        $this->element->setFormFactory($this->formFactory);
        $this->assertTrue($this->element->hasForm());
    }
}

class FooElement extends AbstractAdminElement
{
    public function getId()
    {
        return 'foo.admin.element';
    }

    public function getName()
    {
        return 'foo.admin.element.name';
    }

    protected function getDataGridActionColumnOptions(DataGridInterface $datagrid)
    {
        return array();
    }

    public function delete($entity)
    {
    }

    public function save($entity)
    {
    }

    public function saveGrid()
    {
    }

    protected function initDataGrid()
    {
        $datagrid = $this->getDataGridFactory()->createDataGrid('foo_datagrid');

        return $datagrid;
    }

    protected function initDataSource()
    {
        $datasource = $this->getDataSourceFactory()
            ->createDataSource('doctrine', array(), 'foo_datasource');

        return $datasource;
    }

    protected function initForm($data = null)
    {
        $form = $this->getFormFactory()->createBuilder('foo_form', $data);

        return $form;
    }
}