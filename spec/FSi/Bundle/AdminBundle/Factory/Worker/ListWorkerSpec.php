<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    function let($dataSourceFactory, $dataGridFactory)
    {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_behave_like_list(
        $element,
        $dataSourceFactory,
        $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_implements_list_element(
        $element,
        $dataSourceFactory,
        $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
