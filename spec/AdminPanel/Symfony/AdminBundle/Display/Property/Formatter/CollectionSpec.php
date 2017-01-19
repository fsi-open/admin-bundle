<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CollectionSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter $formatter
     */
    function let($formatter)
    {
        $this->beConstructedWith(array($formatter));
    }

    function it_ignore_empty_values()
    {
        $this->format(0)->shouldReturn(0);
        $this->format(null)->shouldReturn(null);
        $this->format(array())->shouldReturn(array());
    }

    function it_throw_exception_when_value_is_not_an_array()
    {
        $this->shouldThrow(new \InvalidArgumentException("Collection decorator require value to be an array or implement \\Iterator"))
            ->during('format', array(new \stdClass()));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter $formatter
     */
    function it_format_each_element_of_collection_using_formatters($formatter)
    {
        $value = array(
            'first-date' => new \DateTime(),
            'second-date' => new \DateTime()
        );
        $formatter->format(Argument::any())->will(function ($argument) {
            return $argument[0];
        });
        $this->format($value)->shouldReturn($value);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter $formatter
     */
    function it_format_each_element_of_iterator_using_formatters($formatter)
    {
        $value = array(
            'first-date' => new \DateTime(),
            'second-date' => new \DateTime()
        );
        $formatter->format(Argument::any())->will(function ($argument) {
            return $argument[0];
        });
        $this->format(new \ArrayIterator($value))->shouldReturn($value);
    }
}
