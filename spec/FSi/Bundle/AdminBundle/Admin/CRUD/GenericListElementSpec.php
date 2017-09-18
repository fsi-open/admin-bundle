<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FSi\Bundle\AdminBundle\spec\fixtures\MyList;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericListElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class GenericListElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(MyList::class);
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GenericListElement::class);
    }

    function it_is_list_element()
    {
        $this->shouldHaveType(ListElement::class);
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType(Element::class);
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    function it_throws_exception_when_init_datagrid_does_not_return_instance_of_datagrid(DataGridFactoryInterface $factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createDataGrid');
    }

    function it_throws_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactoryInterface $factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createDataSource');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'template_list' => null,
        ]);
    }
}
