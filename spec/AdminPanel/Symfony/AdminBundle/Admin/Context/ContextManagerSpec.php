<?php


namespace spec\AdminPanel\Symfony\AdminBundle\Admin\Context;

use PhpSpec\ObjectBehavior;

class ContextManagerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface $context
     */
    function let($context)
    {
        $this->beConstructedWith(array($context));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface $context
     */
    function it_build_context_for_element($element, $context)
    {
        $context->supports('route_name', $element)->willReturn(true);
        $context->setElement($element)->shouldBeCalled();

        $this->createContext('route_name', $element)->shouldReturn($context);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextInterface $context
     */
    function it_return_null_when_context_builders_do_not_support_element($element, $context)
    {
        $context->supports('route_name', $element)->willReturn(false);
        $context->setElement($element)->shouldNotBeCalled();

        $this->createContext('route_name', $element)->shouldReturn(null);
    }
}
