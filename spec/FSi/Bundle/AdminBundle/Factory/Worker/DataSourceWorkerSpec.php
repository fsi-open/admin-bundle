<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DataSourceWorkerSpec extends ObjectBehavior
{
    function let(DataSourceFactory $dataSourceFactory)
    {
        $this->beConstructedWith($dataSourceFactory);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(AbstractCRUD $element, DataSourceFactory $dataSourceFactory)
    {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
