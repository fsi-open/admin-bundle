<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Context;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use PhpSpec\ObjectBehavior;

class ContextManagerSpec extends ObjectBehavior
{
    function let(ContextBuilderInterface $builder)
    {
        $this->beConstructedWith(array($builder));
    }

    function it_build_context_for_element(
        ElementInterface $element,
        ContextBuilderInterface $builder,
        ContextInterface $context
    ) {
        $builder->supports('route_name', $element)->willReturn(true);
        $builder->buildContext($element)->willReturn($context);

        $this->createContext('route_name', $element)->shouldReturn($context);
    }

    function it_return_null_when_context_builders_do_not_support_element(
        ElementInterface $element,
        ContextBuilderInterface $builder
    ) {
        $builder->supports('route_name', $element)->willReturn(false);
        $builder->buildContext($element)->shouldNotBeCalled();

        $this->createContext('route_name', $element)->shouldReturn(null);
    }
}
