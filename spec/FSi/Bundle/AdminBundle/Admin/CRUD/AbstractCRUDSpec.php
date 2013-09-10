<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactory;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;

class MyCRUD extends AbstractCRUD
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getDataIndexer()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function save($object)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function saveDataGrid()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function delete($object)
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        $datagrid = $factory->createDataGrid('my_datagrid');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:MyEntity'), 'my_datasource');

        return $datasource;
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->create('form');

        return $form;
    }
}

class AbstractCRUDSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\FSi\Bundle\AdminBundle\Admin\CRUD\MyCRUD');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\ElementInterface');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_crud_list');
    }

    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid(DataGridFactory $factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface"))
            ->during('getDataGrid');
    }

    function it_add_batch_column_to_datagrid_when_element_allow_delete_objects(DataGridFactory $factory, DataGrid $datagrid)
    {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', array('display_order' => -1000))->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->getDataGrid()->shouldReturn($datagrid);
    }

    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactory $factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"))
            ->during('getDataSource');
    }

    function it_throw_exception_when_init_form_does_not_return_instance_of_form(FormFactoryInterface $factory)
    {
        $this->setFormFactory($factory);
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('getForm', array(null));
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'allow_delete' => true,
            'allow_add' => true,
            'allow_edit' => true,
            'crud_list_title' => 'crud.list.title',
            'crud_create_title' => 'crud.create.title',
            'crud_edit_title' => 'crud.edit.title',
            'template_crud_list' => null,
            'template_crud_create' => null,
            'template_crud_edit' => null,
            'template_crud_delete' => null,
        ));
    }
}
