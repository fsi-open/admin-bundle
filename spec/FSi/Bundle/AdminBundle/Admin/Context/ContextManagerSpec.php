<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Admin\Element;
use PhpSpec\ObjectBehavior;

class ContextManagerSpec extends ObjectBehavior
{
    function let(ContextInterface $context)
    {
        $this->beConstructedWith([$context]);
    }

    function it_builds_context_for_element(Element $element, ContextInterface $context)
    {
        $context->supports('route_name', $element)->willReturn(true);
        $context->setElement($element)->shouldBeCalled();

        $this->createContext('route_name', $element)->shouldReturn($context);
    }

    function it_return_null_when_context_builders_do_not_support_element(Element $element, ContextInterface $context)
    {
        $context->supports('route_name', $element)->willReturn(false);
        $context->setElement($element)->shouldNotBeCalled();

        $this->createContext('route_name', $element)->shouldReturn(null);
    }
}
