<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractListSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyList');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractList');
    }

    function it_is_list_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\ListElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\ElementInterface');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid(DataGridFactory $factory)
    {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface"))
            ->during('createDataGrid');
    }

    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(DataSourceFactory $factory)
    {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"))
            ->during('createDataSource');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template_list' => null,
        ));
    }
}
