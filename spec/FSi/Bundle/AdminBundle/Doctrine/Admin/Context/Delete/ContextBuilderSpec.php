<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete;

use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Context;
use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ContextBuilderSpec extends ObjectBehavior
{
    function let(Context $context, Request $request, ParameterBag $bag)
    {
        $this->beConstructedWith($context);
        $this->setRequest($request);
        $bag->get('indexes', array())->willReturn(array(1, 2));
        $request->request = $bag;
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element_that_allow_to_delete_elements(
        CRUDElement $element,
        Router $router
    ) {
        $element->getOption('allow_delete')->willReturn(true);

        $this->supports('fsi_admin_crud_delete', $element, $router)->shouldReturn(true);
    }

    function it_should_throw_exception_when_element_does_not_allow_delete(
        CRUDElement $element,
        Request $request
    ) {
        $this->setRequest($request);
        $element->getName()->willReturn('My Element');
        $element->getOption('allow_delete')->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not allow to delete objects"))
            ->during('supports', array('fsi_admin_crud_delete', $element));
    }

    function it_build_context(CRUDElement $element, Context $context)
    {
        $context->setElement($element)->shouldBeCalled();
        $this->buildContext($element)->shouldReturn($context);
    }
}
