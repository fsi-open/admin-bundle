<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class DependentFormElementSpec extends ObjectBehavior
{
    function let(FormFactoryInterface $factory)
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyDependentForm');
        $this->beConstructedWith([]);
        $this->setFormFactory($factory);
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\GenericFormElement');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\FormElement');
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\DependentElement');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_form');
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
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn(null);

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
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');
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
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getRouteParameters()
            ->shouldHaveKeyWithValue(DependentElement::PARENT_REQUEST_PARAMETER, 'parent_object_id');
    }

    function it_throw_exception_when_init_form_does_not_return_instance_of_form(FormFactoryInterface $factory)
    {
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(
            new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface")
        )->during('createForm');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'template_form' => null,
            'allow_add' => true,
        ]);
    }
}
