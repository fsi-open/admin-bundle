<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;

class ListWorkerSpec extends ObjectBehavior
{
    public function let(
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ): void {
        $this->beConstructedWith($dataSourceFactory, $dataGridFactory);
    }

    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_behave_like_list(
        GenericCRUDElement $element,
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ): void {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }

    public function it_mount_datasource_factory_and_datagrid_factory_to_elements_that_implements_list_element(
        ListElement $element,
        DataSourceFactoryInterface $dataSourceFactory,
        DataGridFactoryInterface $dataGridFactory
    ): void {
        $element->setDataSourceFactory($dataSourceFactory)->shouldBeCalled();
        $element->setDataGridFactory($dataGridFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
