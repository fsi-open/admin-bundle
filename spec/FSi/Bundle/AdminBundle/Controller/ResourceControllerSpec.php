<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResourceControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, DelegatingEngine $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_resource');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Controller\ResourceController');
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        AbstractResource $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getName()->willReturn('My Awesome Element');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports My Awesome Element"))
            ->during('resourceAction', array($element, $request));
    }

    function it_render_default_template_in_resource_action(
        Request $request,
        Response $response,
        AbstractResource $element,
        ContextManager $manager,
        Context $context,
        DelegatingEngine $templating
    ) {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_resource', array(), null)->shouldBeCalled()->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_resource_action(
        ContextManager $manager,
        AbstractResource $element,
        Context $context,
        Request $request,
        DelegatingEngine $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_resource_action(
        ContextManager $manager,
        AbstractResource $element,
        Context $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }
}
