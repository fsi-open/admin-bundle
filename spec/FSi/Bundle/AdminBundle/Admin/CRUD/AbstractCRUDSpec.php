<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;

class AbstractCRUDSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyCRUD');
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
            ->during('createDataGrid');
    }

    function it_add_batch_column_to_datagrid_when_element_allow_delete_objects(DataGridFactory $factory, DataGrid $datagrid)
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

    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactory $factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"))
            ->during('createDataSource');
    }

    function it_throw_exception_when_init_form_does_not_return_instance_of_form(FormFactoryInterface $factory)
    {
        $this->setFormFactory($factory);
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('createForm', array(null));
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'allow_delete' => true,
            'allow_add' => true,
            'template_crud_list' => null,
            'template_crud_create' => null,
            'template_crud_edit' => null,
            'template_list' => null,
            'template_form' => null
        ));
    }
}
