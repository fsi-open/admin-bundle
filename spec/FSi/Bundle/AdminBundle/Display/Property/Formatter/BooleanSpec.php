<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BooleanSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('yes', 'no');
    }

    function it_ignore_empty_values()
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format(array())->shouldReturn(array());
    }

    function it_decorate_value()
    {
        $this->format(true)->shouldReturn('yes');
        $this->format(false)->shouldReturn('no');
    }
}
