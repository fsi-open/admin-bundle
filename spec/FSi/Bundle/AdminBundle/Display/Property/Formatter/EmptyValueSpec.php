<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmptyValueSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('-');
    }

    function it_format_empty_values()
    {
        $this->format(0)->shouldReturn('-');
        $this->format(null)->shouldReturn('-');
        $this->format(array())->shouldReturn('-');
    }

    function it_ignore_not_empty_value()
    {
        $datetime = new \DateTime();
        $this->format($datetime)->shouldReturn($datetime);
    }
}
