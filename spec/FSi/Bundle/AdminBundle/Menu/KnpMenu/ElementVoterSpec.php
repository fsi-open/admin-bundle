<?php

namespace spec\FSi\Bundle\AdminBundle\Menu\KnpMenu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementVoterSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    function let($request, $requestAttributes)
    {
        $request->attributes = $requestAttributes;
        $this->setRequest($request);
    }

    function it_is_menu_voter()
    {
        $this->shouldBeAnInstanceOf('\Knp\Menu\Matcher\Voter\VoterInterface');
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    function it_returns_null_if_route_parameters_not_contain_element($item, $requestAttributes)
    {
        $requestAttributes->has('element')->willReturn(false);

        $this->matchItem($item)->shouldReturn(null);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    function it_returns_null_if_route_parameters_contain_element_that_is_not_admin_element($item, $requestAttributes)
    {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn(new \stdClass());

        $this->matchItem($item)->shouldReturn(null);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     */
    function it_returns_false_if_item_has_no_element($item, $requestAttributes, $element)
    {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);

        $item->getExtra('routes', array())->willReturn(array());
        $this->matchItem($item)->shouldReturn(false);

        $item->getExtra('routes', array())->willReturn(array(array(
            'parameters' => array()
        )));
        $this->matchItem($item)->shouldReturn(false);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     */
    function it_returns_false_if_item_has_element_with_different_id_than_in_current_request(
        $item, $requestAttributes, $element
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

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     */
    function it_returns_true_if_item_has_element_with_the_same_id_as_in_current_request(
        $item, $requestAttributes, $element
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

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \FSi\Bundle\AdminBundle\Admin\RedirectableElement $element
     */
    function it_returns_false_if_element_in_current_request_redirects_to_different_element_than_in_item(
        $item, $requestAttributes, $element
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

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \FSi\Bundle\AdminBundle\Admin\RedirectableElement $element
     */
    function it_returns_true_if_element_in_current_request_redirects_to_the_element_in_item(
        $item, $requestAttributes, $element
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

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_return_false_if_request_is_empty($item, $request)
    {
        $request->attributes = null;
        $this->matchItem($item)->shouldReturn(null);
    }
}
