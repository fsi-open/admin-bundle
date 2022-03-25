<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

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
    public function let(): void
    {
        $this->beAnInstanceOf(MyList::class);
        $this->beConstructedWith([]);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(GenericListElement::class);
    }

    public function it_is_list_element(): void
    {
        $this->shouldHaveType(ListElement::class);
    }

    public function it_is_admin_element(): void
    {
        $this->shouldHaveType(Element::class);
    }

    public function it_have_default_route(): void
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    public function it_throws_exception_when_init_datagrid_does_not_return_instance_of_datagrid(
        DataGridFactoryInterface $factory
    ): void {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createDataGrid');
    }

    public function it_throws_exception_when_init_datasource_does_not_return_instance_of_datasource(
        DataSourceFactoryInterface $factory
    ): void {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createDataSource');
    }

    public function it_has_default_options_values(): void
    {
        $this->getOptions()->shouldReturn(
            [
                'template_list' => null,
            ]
        );
    }
}
