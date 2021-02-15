<?php

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;

class EmptyValueSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('-');
    }

    public function it_format_empty_values(): void
    {
        $this->format(0)->shouldReturn('-');
        $this->format(null)->shouldReturn('-');
        $this->format([])->shouldReturn('-');
    }

    public function it_ignore_not_empty_value(): void
    {
        $datetime = new \DateTime();
        $this->format($datetime)->shouldReturn($datetime);
    }
}
