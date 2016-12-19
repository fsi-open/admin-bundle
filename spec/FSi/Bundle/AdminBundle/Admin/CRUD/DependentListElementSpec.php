<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DependentListElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyDependentList');
        $this->beConstructedWith(array());
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\GenericListElement');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\ListElement');
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

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template_list' => null,
        ));
    }
}
