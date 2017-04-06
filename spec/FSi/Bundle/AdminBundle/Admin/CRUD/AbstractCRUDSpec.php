<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;

class AbstractCRUDSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyCRUD');
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid(DataGridFactoryInterface $factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface"))
            ->during('createDataGrid');
    }

    function it_add_batch_column_to_datagrid_when_element_allow_delete_objects(
        DataGridFactoryInterface $factory,
        DataGridInterface $datagrid
    ) {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', [
            'actions' => [
                'delete' => [
                    'route_name' => 'fsi_admin_batch',
                    'additional_parameters' => ['element' => $this->getId()],
                    'label' => 'crud.list.batch.delete'
                ]
            ],
            'display_order' => -1000
        ])->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->createDataGrid()->shouldReturn($datagrid);
    }

    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactoryInterface $factory)
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
            ->during('createForm', [null]);
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
