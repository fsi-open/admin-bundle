<?php

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ListContext;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceControllerSpec extends ObjectBehavior
{
    function let(Container $container, ContextManager $manager, DelegatingEngine $templating, Request $request)
    {
        $container->getParameter('admin.templates.resource')->willReturn('default_resource');
        $container->get('admin.context.manager')->willReturn($manager);
        $container->get('templating')->willReturn($templating);
        $container->get('request')->willReturn($request);
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Controller\ResourceController');
    }

    function it_is_controller()
    {
        $this->shouldBeAnInstanceOf('Symfony\Bundle\FrameworkBundle\Controller\Controller');
    }

    function it_render_default_template_in_resource_action(Request $request, Response $response, AbstractResource $element,
          ContextManager $manager, ListContext $context, DelegatingEngine $templating)
    {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_resource', array(), null)->shouldBeCalled()->willReturn($response);
        $this->resourceAction($element)->shouldReturn($response);
    }

    function it_render_template_from_element_in_resource_action(ContextManager $manager, AbstractResource $element,
        ListContext $context, Request $request, DelegatingEngine $templating, Response $response)
    {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->resourceAction($element)->shouldReturn($response);
    }

    function it_return_response_from_context_in_resource_action(ContextManager $manager, AbstractResource $element,
        ListContext $context, Request $request, Response $response)
    {
        $manager->createContext('fsi_admin_resource', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->resourceAction($element)->shouldReturn($response);
    }
}
