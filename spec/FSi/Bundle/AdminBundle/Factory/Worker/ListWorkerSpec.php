<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    function let(DataSourceFactory $dataSourceFactory, DataGridFactory $dataGridFactory)
    {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_are_datagrid_aware(
        AbstractCRUD $element,
        DataSourceFactory $dataSourceFactory,
        DataGridFactory $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
