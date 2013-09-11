<?php

namespace spec\FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class AdminEventSpec extends ObjectBehavior
{
    function let(ElementInterface $element, Request $request)
    {
        $this->beConstructedWith($element, $request);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Event\AdminEvent');
    }

    function it_is_event()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\Event');
    }
}
