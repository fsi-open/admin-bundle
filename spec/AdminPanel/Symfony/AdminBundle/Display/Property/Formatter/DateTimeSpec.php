<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateTimeSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Y:m:d H:i:s');
    }

    function it_ignore_empty_values_and_values_that_are_not_datetime()
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format(array())->shouldReturn(array());
        $this->format('not_datetime')->shouldReturn('not_datetime');
    }

    function it_decorate_value()
    {
        $datetime = new \DateTime();
        $this->format($datetime)->shouldReturn($datetime->format("Y:m:d H:i:s"));
    }
}
