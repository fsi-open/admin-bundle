<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\Translatable\LocaleProvider;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\Bundle\AdminBundle\spec\fixtures\MyCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class GenericCRUDElementSpec extends ObjectBehavior
{
    public function let(LocaleProvider $localeProvider): void
    {
        $localeProvider->getLocale()->willReturn('en');
        $this->beAnInstanceOf(MyCRUD::class);
        $this->beConstructedWith([]);
        $this->setLocaleProvider($localeProvider);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(GenericCRUDElement::class);
    }

    public function it_is_admin_element(): void
    {
        $this->shouldHaveType(Element::class);
    }

    public function it_has_default_route(): void
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    public function it_throws_exception_when_init_datagrid_does_not_return_datagrid(
        DataGridFactoryInterface $factory
    ): void {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)->during('createDataGrid');
    }

    public function it_adds_batch_column_to_datagrid_when_element_allow_delete_objects(
        DataGridFactoryInterface $factory,
        DataGridInterface $datagrid
    ): void {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn(
            'batch',
            'batch',
            [
                'actions' => [
                    'delete' => [
                        'route_name' => 'fsi_admin_batch',
                        'additional_parameters' => ['element' => $this->getId()],
                        'label' => 'crud.list.batch.delete',
                    ],
                ],
                'display_order' => -1000,
                'translation_domain' => 'FSiAdminBundle'
            ]
        )->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->createDataGrid()->shouldReturn($datagrid);
    }

    public function it_throws_exception_when_init_datasource_does_not_return_instance_of_datasource(
        DataSourceFactoryInterface $factory
    ): void {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createDataSource');
    }

    public function it_throws_exception_when_init_form_does_not_return_form(FormFactoryInterface $factory): void
    {
        $this->setFormFactory($factory);
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)
            ->during('createForm', [null]);
    }

    public function it_has_default_options_values(): void
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
