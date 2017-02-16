<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\KnpMenu;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use Knp\Menu\ItemInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class ElementVoterSpec extends ObjectBehavior
{
    function let(Request $request, ParameterBag $requestAttributes)
    {
        $request->attributes = $requestAttributes;
        $this->setRequest($request);
    }

    function it_is_menu_voter()
    {
        $this->shouldBeAnInstanceOf('\Knp\Menu\Matcher\Voter\VoterInterface');
    }

    function it_returns_null_if_route_parameters_not_contain_element(
        ItemInterface $item,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->has('element')->willReturn(false);

        $this->matchItem($item)->shouldReturn(null);
    }

    function it_returns_null_if_route_parameters_contain_element_that_is_not_admin_element(
        ItemInterface $item,
        ParameterBag $requestAttributes
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn(new \stdClass());

        $this->matchItem($item)->shouldReturn(null);
    }

    function it_returns_false_if_item_has_no_element(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);

        $item->getExtra('routes', array())->willReturn(array());
        $this->matchItem($item)->shouldReturn(false);

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array()
        )));
        $this->matchItem($item)->shouldReturn(false);
    }

    function it_returns_false_if_item_has_element_with_different_id_than_in_current_request(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array(
                'element' => 'some_element'
            )
        )));
        $this->matchItem($item)->shouldReturn(false);
    }

    function it_returns_true_if_item_has_element_with_the_same_id_as_in_current_request(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        Element $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array(
                'element' => 'element_id'
            )
        )));
        $this->matchItem($item)->shouldReturn(true);
    }

    function it_returns_false_if_element_in_current_request_redirects_to_different_element_than_in_item(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        RedirectableElement $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_after_success'));

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array(
                'element' => 'some_element'
            )
        )));
        $this->matchItem($item)->shouldReturn(false);
    }

    function it_returns_true_if_element_in_current_request_redirects_to_the_element_in_item(
        ItemInterface $item,
        ParameterBag $requestAttributes,
        RedirectableElement $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_after_success'));

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array(
                'element' => 'element_after_success'
            )
        )));
        $this->matchItem($item)->shouldReturn(true);
    }

    function it_return_false_if_request_is_empty(ItemInterface $item, Request $request)
    {
        $request->attributes = null;
        $this->matchItem($item)->shouldReturn(null);
    }
}
