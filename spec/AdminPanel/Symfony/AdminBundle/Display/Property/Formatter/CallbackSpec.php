<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(function ($value) {
            return $value . '+';
        });
    }

    public function it_ignore_empty_values()
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format([])->shouldReturn([]);
    }

    public function it_form_value_using_callback_funciton()
    {
        $value = 'value';
        $this->format($value)->shouldReturn('value+');
    }
}
