<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EmptyValueSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('-');
    }

    public function it_format_empty_values()
    {
        $this->format(0)->shouldReturn('-');
        $this->format(null)->shouldReturn('-');
        $this->format([])->shouldReturn('-');
    }

    public function it_ignore_not_empty_value()
    {
        $datetime = new \DateTime();
        $this->format($datetime)->shouldReturn($datetime);
    }
}
