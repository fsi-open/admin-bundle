<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;
use stdClass;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ElementVoterSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $manager,
        Request $request,
        ParameterBag $requestAttributes,
        RequestStack $requestStack
    ): void {
        $request->attributes = $requestAttributes;
        $requestStack->getCurrentRequest()->willReturn($request);

        $this->beConstructedWith($manager, $requestStack);
    }

    public function it_is_menu_voter(): void
    {
        $this->shouldBeAnInstanceOf(VoterInterface::class);
    }

    public function it_returns_null_if_route_parameters_not_contain_element(
        ItemInterface $item,
        ParameterBag $requestAttributes
    ): void {
        $requestAttributes->has('element')->willReturn(false);

        $this->matchItem($item)->shouldReturn(null);
    }

    public function it_returns_null_if_route_parameters_contain_element_that_is_not_admin_element(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        stdClass $object
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($object);

        $this->matchItem($item)->shouldReturn(null);
    }

    public function it_returns_false_if_item_has_no_element(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);

        $item->getExtra('routes', [])->willReturn([]);
        $this->matchItem($item)->shouldReturn(false);

        $item->getExtra('routes', [])->willReturn(
            [
                [
                    'parameters' => [],
                ],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_false_if_item_has_element_with_different_id_than_in_current_request(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                [
                    'parameters' => ['element' => 'some_element'],
                ],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_item_has_element_with_the_same_id_as_in_current_request(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                [
                    'parameters' => ['element' => 'element_id'],
                ],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_element_in_current_request_redirects_to_different_element_than_in_item(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        RedirectableElement $element
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                [
                    'parameters' => ['element' => 'some_element'],
                ],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_element_in_current_request_redirects_to_the_element_in_item(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        RedirectableElement $element
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                [
                    'parameters' => ['element' => 'element_after_success'],
                ],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_item_has_element_with_different_id_than_parent_of_element_in_current_request(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        Element $parentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'some_element']],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_item_has_element_with_the_same_id_as_parent_of_element_in_current_request(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        Element $parentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'parent_element_id']],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_parent_of_element_in_current_request_redirects_to_different_element_than_menu(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        RedirectableElement $parentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getSuccessRouteParameters()->willReturn(['element' => 'parent_element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'some_element']],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_parent_of_element_in_current_request_redirects_to_the_element_in_item(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        RedirectableElement $parentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getSuccessRouteParameters()->willReturn(['element' => 'parent_element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'parent_element_after_success']],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_item_has_element_with_different_id_than_grandparent_of_element_in_request(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        DependentElement $parentElement,
        Element $grandparentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getParentId()->willReturn('grandparent_element_id');
        $manager->hasElement('grandparent_element_id')->willReturn(true);
        $manager->getElement('grandparent_element_id')->willReturn($grandparentElement);
        $grandparentElement->getId()->willReturn('grandparent_element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'some_element']],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_item_has_element_with_the_same_id_as_grandparent_of_element_in_current_request(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        DependentElement $parentElement,
        Element $grandparentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getParentId()->willReturn('grandparent_element_id');
        $manager->hasElement('grandparent_element_id')->willReturn(true);
        $manager->getElement('grandparent_element_id')->willReturn($grandparentElement);
        $grandparentElement->getId()->willReturn('grandparent_element_id');

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'grandparent_element_id']],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_grandparent_of_element_in_request_redirects_to_different_element_than_menu(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        DependentElement $parentElement,
        RedirectableElement $grandparentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getParentId()->willReturn('grandparent_element_id');
        $manager->hasElement('grandparent_element_id')->willReturn(true);
        $manager->getElement('grandparent_element_id')->willReturn($grandparentElement);
        $grandparentElement->getId()->willReturn('grandparent_element_id');
        $grandparentElement->getSuccessRouteParameters()
            ->willReturn(['element' => 'grandparent_element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'some_element']],
            ]
        );
        $this->matchItem($item)->shouldReturn(false);
    }

    public function it_returns_true_if_grandparent_of_element_in_current_request_redirects_to_the_element_in_item(
        ManagerInterface $manager,
        ItemInterface $item,
        ParameterBag $requestAttributes,
        DependentElement $element,
        DependentElement $parentElement,
        RedirectableElement $grandparentElement
    ): void {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getParentId()->willReturn('parent_element_id');
        $manager->hasElement('parent_element_id')->willReturn(true);
        $manager->getElement('parent_element_id')->willReturn($parentElement);
        $parentElement->getId()->willReturn('parent_element_id');
        $parentElement->getParentId()->willReturn('grandparent_element_id');
        $manager->hasElement('grandparent_element_id')->willReturn(true);
        $manager->getElement('grandparent_element_id')->willReturn($grandparentElement);
        $grandparentElement->getId()->willReturn('grandparent_element_id');
        $grandparentElement->getSuccessRouteParameters()
            ->willReturn(['element' => 'grandparent_element_after_success']);

        $item->getExtra('routes', [])->willReturn(
            [
                ['parameters' => ['element' => 'grandparent_element_after_success']],
            ]
        );
        $this->matchItem($item)->shouldReturn(true);
    }

    public function it_returns_false_if_request_is_empty(ItemInterface $item, Request $request): void
    {
        $request->attributes = new ParameterBag();
        $this->matchItem($item)->shouldReturn(null);
    }
}
