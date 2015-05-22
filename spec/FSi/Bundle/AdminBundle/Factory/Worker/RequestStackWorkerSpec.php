<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestStackWorkerSpec extends ObjectBehavior
{
    function let(RequestStack $requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    function it_mounts_request_stack_to_elements_that_are_request_stack_aware(
        AbstractElement $element,
        RequestStack $requestStack
    ) {
        $element->setRequestStack($requestStack)->shouldBeCalled();

        $this->mount($element);
    }
}
