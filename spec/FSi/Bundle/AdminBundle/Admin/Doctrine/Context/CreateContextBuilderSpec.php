<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

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
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\CreateContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element_that_allows_adding_new_elements(CRUDElement $element, Router $router)
    {
        $element->getOption('allow_add')->shouldBeCalleD()->willReturn(true);
        $this->supports('fsi_admin_crud_create', $element, $router)->shouldReturn(true);
    }

    function it_build_context(CRUDElement $element)
    {
        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\CreateContext');
    }
}
