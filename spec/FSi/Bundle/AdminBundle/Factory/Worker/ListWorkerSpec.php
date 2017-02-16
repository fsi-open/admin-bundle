<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\DataGridAwareElement;
use FSi\Bundle\AdminBundle\spec\fixtures\Admin\DataSourceAwareElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    function let(
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(
        DataGridAwareElement $element,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datasource_aware(
        DataSourceAwareElement $element,
        DataSourceFactoryInterface $dataSourceFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();

        $this->mount($element);
    }

    function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_behave_like_list(
        AbstractCRUD $element,
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_implements_list_element(
        ListElement $element,
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
