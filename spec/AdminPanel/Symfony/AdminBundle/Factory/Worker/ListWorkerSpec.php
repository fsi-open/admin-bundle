<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    public function let($dataSourceFactory, $dataGridFactory)
    {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\DataGridAwareElement $element
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    public function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware($element, $dataGridFactory)
    {
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\DataSourceAwareElement $element
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     */
    public function it_mount_datagrid_factory_to_elements_that_are_datasource_aware($element, $dataSourceFactory)
    {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_behave_like_list(
        $element, $dataSourceFactory, $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement $element
     * @param \FSi\Component\DataSource\DataSourceFactory $dataSourceFactory
     * @param \FSi\Component\DataGrid\DataGridFactory $dataGridFactory
     */
    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_implements_list_element(
        $element, $dataSourceFactory, $dataGridFactory
    ) {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
