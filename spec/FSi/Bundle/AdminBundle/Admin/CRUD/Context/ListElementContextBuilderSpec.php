<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use PhpSpec\ObjectBehavior;

class ListElementContextBuilderSpec extends ObjectBehavior
{
    function let(ListElementContext $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(ListElement $element)
    {
        $this->supports('fsi_admin_list', $element)->shouldReturn(true);
    }

    function it_build_context(ListElementContext $context, ListElement $element)
    {
        $context->setElement($element)->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }
}
