<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Decorator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateTimeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Y:m:d H:i:s');
    }

    function it_throw_exception_when_value_is_not_datetime_object()
    {
        $this->shouldThrow(new \InvalidArgumentException("DateTime decorator require value to be an instance of \\DateTime"))->during('decorate', array(new \stdClass()));
    }

    function it_ignore_empty_values()
    {
        $this->decorate(0)->shouldReturn(0);
        $this->decorate(null)->shouldReturn(null);
        $this->decorate(array())->shouldReturn(array());
    }

    function it_decorate_value()
    {
        $datetime = new \DateTime();
        $this->decorate($datetime)->shouldReturn($datetime->format("Y:m:d H:i:s"));
    }
}
