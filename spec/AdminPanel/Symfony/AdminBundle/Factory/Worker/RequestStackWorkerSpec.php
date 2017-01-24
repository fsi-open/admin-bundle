<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

class RequestStackWorkerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function let($requestStack)
    {
        $this->beConstructedWith($requestStack);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin\RequestStackAwareElement $element
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function it_mount_request_stack_to_elements_that_are_request_stack_aware($element, $requestStack)
    {
        $element->setRequestStack($requestStack)->shouldBeCalled();

        $this->mount($element);
    }
}
