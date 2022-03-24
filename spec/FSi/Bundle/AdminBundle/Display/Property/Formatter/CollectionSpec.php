<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior
{
    public function let(ValueFormatter $formatter): void
    {
        $this->beConstructedWith([$formatter]);
    }

    public function it_ignore_empty_values(): void
    {
        $this->format(null)->shouldReturn(null);
        $this->format([])->shouldReturn([]);
    }

    public function it_throw_exception_when_value_is_not_an_array(): void
    {
        $this->shouldThrow(
            new \InvalidArgumentException('Collection formatter requires value to be iterable, stdClass given')
        )->during('format', [new \stdClass()]);
    }

    public function it_format_each_element_of_collection_using_formatters(ValueFormatter $formatter): void
    {
        $value = [
            'first-date' => new \DateTime(),
            'second-date' => new \DateTime(),
        ];
        $formatter->format(Argument::any())->will(
            function ($argument) {
                return $argument[0];
            }
        );
        $this->format($value)->shouldReturn($value);
    }

    public function it_format_each_element_of_iterator_using_formatters(ValueFormatter $formatter): void
    {
        $value = [
            'first-date' => new \DateTime(),
            'second-date' => new \DateTime(),
        ];
        $formatter->format(Argument::any())->will(
            function ($argument) {
                return $argument[0];
            }
        );
        $this->format(new \ArrayIterator($value))->shouldReturn($value);
    }
}
