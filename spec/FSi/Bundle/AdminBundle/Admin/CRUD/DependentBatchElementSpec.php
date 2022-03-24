<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataIndexerElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use FSi\Bundle\AdminBundle\spec\fixtures\MyDependentBatch;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;

class DependentBatchElementSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beAnInstanceOf(MyDependentBatch::class);
        $this->beConstructedWith([]);
    }

    public function it_is_admin_element(): void
    {
        $this->shouldHaveType(GenericBatchElement::class);
        $this->shouldHaveType(BatchElement::class);
        $this->shouldHaveType(DependentElement::class);
    }

    public function it_have_default_route(): void
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }

    public function it_returns_null_if_parent_element_does_not_have_data_indexer(
        RequestStack $requestStack,
        Request $currentRequest,
        Element $parentElement
    ): void {
        $requestStack->getCurrentRequest()->willReturn($currentRequest);

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn(null);
    }

    public function it_returns_null_if_parent_object_id_is_not_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn(null);

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn(null);
    }

    public function it_returns_parent_object_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');
        $parentDataIndexer->getData('parent_object_id')->willReturn('parent_object');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getParentObject()->shouldReturn('parent_object');
    }

    public function its_route_parameters_contain_parent_object_id_if_its_available(
        RequestStack $requestStack,
        Request $currentRequest,
        DataIndexerElement $parentElement,
        DataIndexerInterface $parentDataIndexer
    ): void {
        $parentElement->getDataIndexer()->willReturn($parentDataIndexer);
        $requestStack->getCurrentRequest()->willReturn($currentRequest);
        $currentRequest->get(DependentElement::PARENT_REQUEST_PARAMETER)->willReturn('parent_object_id');

        $this->setRequestStack($requestStack);
        $this->setParentElement($parentElement);

        $this->getRouteParameters()
            ->shouldHaveKeyWithValue(DependentElement::PARENT_REQUEST_PARAMETER, 'parent_object_id');
    }
}
