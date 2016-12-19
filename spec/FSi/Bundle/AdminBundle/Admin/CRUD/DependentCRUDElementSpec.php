<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DependentCRUDElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyDependentCRUD');
        $this->beConstructedWith(array());
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\DependentCRUDElement');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\CRUDElement');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\DependentElement');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_list');
    }

    function it_returns_null_if_parent_element_does_not_have_data_indexer(
        RequestStack $requestStack,
        Request $currentRequest,
        Element $parentElement
    ) {
        $requestStack->getCurrentRequest()->willReturn($currentRequest);

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn(null);
    }

    function it_returns_null_if_parent_object_id_is_not_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ) {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::REQUEST_PARENT_PARAMETER)->willReturn(null);

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn(null);
    }

    function it_returns_parent_object_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ) {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::REQUEST_PARENT_PARAMETER)->willReturn('parent_object_id');
        $parentDataIndexer->getData('parent_object_id')->willReturn('parent_object');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn('parent_object');
    }

    function its_route_parameters_contain_parent_object_id_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ) {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::REQUEST_PARENT_PARAMETER)->willReturn('parent_object_id');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getRouteParameters()
            ->shouldHaveKeyWithValue(DependentElement::REQUEST_PARENT_PARAMETER, 'parent_object_id');
    }

    function it_throw_exception_when_init_datagrid_does_not_return_instance_of_datagrid(
        DataGridFactoryInterface $factory
    ) {
        $this->setDataGridFactory($factory);
        $factory->createDataGrid(Argument::cetera())->willReturn(null);

        $this->shouldThrow(
            new RuntimeException("initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface")
        )->during('createDataGrid');
    }

    function it_add_batch_column_to_datagrid_when_element_allow_delete_objects(
        DataGridFactoryInterface $factory,
        DataGridInterface $datagrid
    ) {
        $factory->createDataGrid('my_datagrid')->shouldBeCalled()->willReturn($datagrid);
        $datagrid->hasColumnType('batch')->shouldBeCalled()->willReturn(false);
        $datagrid->addColumn('batch', 'batch', array(
            'actions' => array(
                'delete' => array(
                    'route_name' => 'fsi_admin_batch',
                    'additional_parameters' => array('element' => $this->getId()),
                    'label' => 'crud.list.batch.delete'
                )
            ),
            'display_order' => -1000
        ))->shouldBeCalled();

        $this->setDataGridFactory($factory);

        $this->createDataGrid()->shouldReturn($datagrid);
    }

    function it_throw_exception_when_init_datasource_does_not_return_instance_of_datasource(
        DataSourceFactoryInterface $factory
    ) {
        $this->setDataSourceFactory($factory);
        $factory->createDataSource(Argument::cetera())->willReturn(null);

        $this->shouldThrow(
            new RuntimeException(
                "initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface"
            )
        )->during('createDataSource');
    }

    function it_throw_exception_when_init_form_does_not_return_instance_of_form(FormFactoryInterface $factory)
    {
        $this->setFormFactory($factory);
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(
            new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface")
        )->during('createForm', array(null));
    }

    function it_has_default_options_values()
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
