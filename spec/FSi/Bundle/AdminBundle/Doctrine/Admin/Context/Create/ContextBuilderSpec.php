<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create\Context;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use PhpSpec\ObjectBehavior;

class ContextBuilderSpec extends ObjectBehavior
{
    function let(Context $context)
    {
        $this->beConstructedWith($context);
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element_that_allows_adding_new_objects(CRUDElement $element)
    {
        $element->getOption('allow_add')->willReturn(true);
        $this->supports('fsi_admin_crud_create', $element)->shouldReturn(true);
    }

    function it_throws_exception_when_element_does_not_allow_to_create_objects(CRUDElement $element)
    {
        $element->getId()->willReturn('my_element');
        $element->getOption('allow_add')->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("Element with id \"my_element\" does not allow to create objects"))
            ->during('supports', array('fsi_admin_crud_create', $element));
    }

    function it_build_context(Context $context, CRUDElement $element)
    {
        $context->setElement($element)->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }
}
