<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function($value) {
            return $value . '+';
        });
    }

    function it_ignore_empty_values()
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format(array())->shouldReturn(array());
    }

    function it_form_value_using_callback_funciton()
    {
        $value = 'value';
        $this->format($value)->shouldReturn('value+');
    }
}
