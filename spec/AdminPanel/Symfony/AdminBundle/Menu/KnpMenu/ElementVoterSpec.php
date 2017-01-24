<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Menu\KnpMenu;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementVoterSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    public function let($request, $requestAttributes)
    {
        $request->attributes = $requestAttributes;
        $this->setRequest($request);
    }

    public function it_is_menu_voter()
    {
        $this->shouldBeAnInstanceOf('\Knp\Menu\Matcher\Voter\VoterInterface');
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    public function it_returns_null_if_route_parameters_not_contain_element($item, $requestAttributes)
    {
        $requestAttributes->has('element')->willReturn(false);

        $this->matchItem($item)->shouldReturn(null);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     */
    public function it_returns_null_if_route_parameters_contain_element_that_is_not_admin_element($item, $requestAttributes)
    {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn(new \stdClass());

        $this->matchItem($item)->shouldReturn(null);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    public function it_returns_false_if_item_has_no_element($item, $requestAttributes, $element)
    {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);

        $item->getExtra('routes', [])->willReturn([]);
        $this->matchItem($item)->shouldReturn(false);

        $item->getExtra('routes', [])->willReturn([[
            'parameters' => []
        ]]);
        $this->matchItem($item)->shouldReturn(false);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    public function it_returns_false_if_item_has_element_with_different_id_than_in_current_request(
        $item, $requestAttributes, $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', [])->willReturn([[
            'parameters' => [
                'element' => 'some_element'
            ]
        ]]);
        $this->matchItem($item)->shouldReturn(false);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    public function it_returns_true_if_item_has_element_with_the_same_id_as_in_current_request(
        $item, $requestAttributes, $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');

        $item->getExtra('routes', [])->willReturn([[
            'parameters' => [
                'element' => 'element_id'
            ]
        ]]);
        $this->matchItem($item)->shouldReturn(true);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement $element
     */
    public function it_returns_false_if_element_in_current_request_redirects_to_different_element_than_in_item(
        $item, $requestAttributes, $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_after_success']);

        $item->getExtra('routes', [])->willReturn([[
            'parameters' => [
                'element' => 'some_element'
            ]
        ]]);
        $this->matchItem($item)->shouldReturn(false);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\ParameterBag $requestAttributes
     * @param \AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement $element
     */
    public function it_returns_true_if_element_in_current_request_redirects_to_the_element_in_item(
        $item, $requestAttributes, $element
    ) {
        $requestAttributes->has('element')->willReturn(true);
        $requestAttributes->get('element')->willReturn($element);
        $element->getId()->willReturn('element_id');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_after_success']);

        $item->getExtra('routes', [])->willReturn([[
            'parameters' => [
                'element' => 'element_after_success'
            ]
        ]]);
        $this->matchItem($item)->shouldReturn(true);
    }

    /**
     * @param \Knp\Menu\ItemInterface $item
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_return_false_if_request_is_empty($item, $request)
    {
        $request->attributes = null;
        $this->matchItem($item)->shouldReturn(null);
    }
}
