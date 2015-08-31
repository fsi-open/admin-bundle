<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class RequestStackWorkerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    function let($requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\spec\fixtures\Admin\RequestStackAwareElement $element
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    function it_mount_request_stack_to_elements_that_are_request_stack_aware($element, $requestStack)
    {
        $element->setRequestStack($requestStack)->shouldBeCalled();

        $this->mount($element);
    }
}
