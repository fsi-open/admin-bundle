<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BatchElementContextBuilderSpec extends ObjectBehavior
{
    function let(BatchElementContext $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_batch_element(BatchElement $element)
    {
        $this->supports('fsi_admin_batch', $element)->shouldReturn(true);
    }

    function it_builds_context(BatchElementContext $context, BatchElement $element)
    {
        $context->setElement($element)->shouldBeCalled();

        $this->buildContext($element)->shouldReturn($context);
    }
}
