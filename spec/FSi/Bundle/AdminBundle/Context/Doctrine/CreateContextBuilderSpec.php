<?php

namespace spec\FSi\Bundle\AdminBundle\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Routing\Router;

class CreateContextBuilderSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, Router $router)
    {
        $this->beConstructedWith($dispatcher, $router);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Context\Doctrine\CreateContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(CRUDElement $element, Router $router)
    {
        $element->hasCreateForm()->shouldBeCalled()->willReturn(true);
        $this->supports('fsi_admin_crud_create', $element, $router)->shouldReturn(true);
    }


    function it_throws_exception_when_doctrine_crud_element_does_not_have_create_form(CRUDElement $element,
        Router $router)
    {
        $element->getName()->shouldBeCalled()->willReturn('My Element');
        $element->hasCreateForm()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not have create form"))
            ->during('supports', array('fsi_admin_crud_create', $element, $router));
    }

    function it_build_context(CRUDElement $element)
    {
        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Context\Doctrine\CreateContext');
    }
}
