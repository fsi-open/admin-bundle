<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractCRUDSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyCRUD');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    /**
     * @param \FSi\Component\DataGrid\DataGridFactory $factory
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     */
    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid($factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface"))
            ->during('createDataGrid');
    }

    /**
     * @param \FSi\Component\DataGrid\DataGridFactory $factory
     * @param \FSi\Component\DataGrid\DataGrid $datagrid
     * @throws \FSi\Component\DataGrid\Exception\DataGridColumnException
     * @throws \FSi\Component\DataGrid\Exception\UnexpectedTypeException
     */
    function it_add_batch_column_to_datagrid_when_element_allow_delete_objects($factory, $datagrid)
    {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', array(
            'actions' => array(
                'delete' => array(
                    'route_name' => 'fsi_admin_batch',
                    'additional_parameters' => array('element' => $this->getId()),
                    'label' => 'crud.list.batch.delete'
                )
            ),
            'display_order' => -1000
        ))->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->createDataGrid()->shouldReturn($datagrid);
    }

    /**
     * @param \FSi\Component\DataSource\DataSourceFactory $factory
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource($factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"))
            ->during('createDataSource');
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    function it_throw_exception_when_init_form_does_not_return_instance_of_form($factory)
    {
        $this->setFormFactory($factory);
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('createForm', array(null));
    }

    function it_has_default_options_values()
    {
        $options = $this->getOptions();
        $options->shouldHaveKey('allow_delete');
        $options->shouldHaveKey('allow_add');
        $options->shouldHaveKey('template_crud_list');
        $options->shouldHaveKey('template_crud_create');
        $options->shouldHaveKey('template_crud_edit');
        $options->shouldHaveKey('template_list');
        $options->shouldHaveKey('template_form');
        $options['allow_delete']->shouldBe(true);
        $options['allow_add']->shouldBe(true);
        $options['template_crud_list']->shouldBe(null);
        $options['template_crud_create']->shouldBe(null);
        $options['template_crud_edit']->shouldBe(null);
        $options['template_list']->shouldBe(null);
        $options['template_form']->shouldBe(null);
    }
}
