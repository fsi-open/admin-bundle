<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSource;
use FSi\Component\DataSource\DataSourceFactory;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
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

    protected function initDataGrid(DataGridFactory $factory)
    {
        $datagrid = $factory->createDataGrid('my_datagrid');

        return $datagrid;
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        $datasource = $factory->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:MyEntity'), 'my_datasource');

        return $datasource;
    }

    protected function initCreateForm(FormFactoryInterface $factory)
    {
        $form = $factory->createNamed('create', 'form');

        return $form;
    }

    protected function initEditForm(FormFactoryInterface $factory, $data = null)
    {
        $form = $factory->createNamed('edit', 'form');

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

    function it_has_defualt_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_crud_list');
    }

    function it_has_datagrid(DataGridFactory $factory, DataGrid $datagrid)
    {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $this->setDataGridFactory($factory);
        $this->hasDataGrid()->shouldReturn(true);
    }

    function it_build_datagrid_only_once(DataGridFactory $factory, DataGrid $datagrid)
    {
        $factory->createDataGrid('my_datagrid')->shouldBeCalledTimes(1)->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', array('display_order' => -1000))->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->getDataGrid()->shouldReturn($datagrid);
        $this->getDataGrid()->shouldReturn($datagrid);
    }

    function it_build_datasource_without_batch_column(DataGridFactory $factory, DataGrid $datagrid)
    {
        $this->beAnInstanceOf('spec\FSi\Bundle\AdminBundle\Admin\CRUD\MyCRUD');
        $this->beConstructedWith(array(
            'allow_delete' => false
        ));

        $factory->createDataGrid('my_datagrid')->shouldBeCalledTimes(1)->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldNotBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', array('display_order' => -1000))->shouldNotBeCalled();

        $this->setDataGridFactory($factory);

        $this->getDataGrid()->shouldReturn($datagrid);
    }

    function it_has_datasource(DataSourceFactory $factory, DataSource $datasource)
    {
        $factory->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:MyEntity'), 'my_datasource')
            ->shouldBeCalled()
            ->willReturn($datasource);

        $this->setDataSourceFactory($factory);
        $this->hasDataSource()->shouldReturn(true);
    }

    function it_build_datasource_only_once(DataSourceFactory $factory, DataSource $datasource)
    {
        $factory->createDataSource('doctrine', array('entity' => 'FSiDemoBundle:MyEntity'), 'my_datasource')
            ->shouldBeCalledTimes(1)
            ->willReturn($datasource);

        $this->setDataSourceFactory($factory);

        $this->getDataSource()->shouldReturn($datasource);
        $this->getDataSource()->shouldReturn($datasource);
    }

    function it_has_create_form(FormFactory $factory, Form $form)
    {
        $factory->createNamed('create', 'form')->shouldBeCalled()->willReturn($form);

        $this->setFormFactory($factory);
        $this->hasCreateForm()->shouldReturn(true);
    }

    function it_build_create_form_only_once(FormFactory $factory, Form $form)
    {
        $factory->createNamed('create', 'form')->shouldBeCalled()->willReturn($form);

        $this->setFormFactory($factory);
        $this->getCreateForm()->shouldReturn($form);
        $this->getCreateForm()->shouldReturn($form);
    }

    function it_has_edit_form(FormFactory $factory, Form $form)
    {
        $factory->createNamed('edit', 'form')->shouldBeCalled()->willReturn($form);

        $this->setFormFactory($factory);
        $this->hasEditForm()->shouldReturn(true);
    }

    function it_build_edit_form_only_once(FormFactory $factory, Form $form)
    {
        $factory->createNamed('edit', 'form')->shouldBeCalledTimes(1)->willReturn($form);

        $this->setFormFactory($factory);
        $this->getEditForm()->shouldReturn($form);
        $this->getEditForm()->shouldReturn($form);
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'allow_delete' => true,
            'allow_add' => true,
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
