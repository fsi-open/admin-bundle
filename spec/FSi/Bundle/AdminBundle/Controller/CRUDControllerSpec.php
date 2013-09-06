<?php

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\Doctrine\Context\CreateContext;
use FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ListContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CRUDControllerSpec extends ObjectBehavior
{
    function let(Container $container, ContextManager $manager, DelegatingEngine $templating, Request $request)
    {
        $container->getParameter('admin.templates.crud_list')->willReturn('default_crud_list');
        $container->getParameter('admin.templates.crud_create')->willReturn('default_crud_create');
        $container->getParameter('admin.templates.crud_edit')->willReturn('default_crud_edit');
        $container->getParameter('admin.templates.crud_delete')->willReturn('default_crud_delete');
        $container->get('admin.context.manager')->willReturn($manager);
        $container->get('templating')->willReturn($templating);
        $container->get('request')->willReturn($request);
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Controller\CRUDController');
    }

    function it_is_controller()
    {
        $this->shouldBeAnInstanceOf('Symfony\Bundle\FrameworkBundle\Controller\Controller');
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(AbstractCRUD $element, ContextManager $manager)
    {
        $element->getName()->willReturn('My Awesome Element');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports My Awesome Element"))
            ->during('listAction', array($element));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports My Awesome Element"))
            ->during('createAction', array($element));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports My Awesome Element"))
            ->during('editAction', array($element));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports My Awesome Element"))
            ->during('deleteAction', array($element));
    }

    function it_render_default_template_in_list_action(Request $request, Response $response, AbstractCRUD $element,
          ContextManager $manager, ListContext $context, DelegatingEngine $templating)
    {
        $manager->createContext('fsi_admin_crud_list', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_list', array(), null)->shouldBeCalled()->willReturn($response);
        $this->listAction($element)->shouldReturn($response);
    }

    function it_render_template_from_element_in_list_action(ContextManager $manager, AbstractCRUD $element,
        ListContext $context, Request $request, DelegatingEngine $templating, Response $response)
    {
        $manager->createContext('fsi_admin_crud_list', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->listAction($element)->shouldReturn($response);
    }

    function it_return_response_from_context_in_list_action(ContextManager $manager, AbstractCRUD $element,
        ListContext $context, Request $request, Response $response)
    {
        $manager->createContext('fsi_admin_crud_list', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->listAction($element)->shouldReturn($response);
    }

    function it_render_default_template_in_create_action(Request $request, Response $response, AbstractCRUD $element,
       ContextManager $manager, CreateContext $context, DelegatingEngine $templating)
    {
        $manager->createContext('fsi_admin_crud_create', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_create', array(), null)->shouldBeCalled()->willReturn($response);
        $this->createAction($element)->shouldReturn($response);
    }

    function it_render_template_from_element_in_create_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, DelegatingEngine $templating, Response $response)
    {
        $manager->createContext('fsi_admin_crud_create', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->createAction($element)->shouldReturn($response);
    }

    function it_return_response_from_context_in_create_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, Response $response)
    {
        $manager->createContext('fsi_admin_crud_create', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->createAction($element)->shouldReturn($response);
    }

    function it_render_default_template_in_edit_action(Request $request, Response $response, AbstractCRUD $element,
         ContextManager $manager, CreateContext $context, DelegatingEngine $templating)
    {
        $manager->createContext('fsi_admin_crud_edit', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_edit', array(), null)->shouldBeCalled()->willReturn($response);
        $this->editAction($element)->shouldReturn($response);
    }

    function it_render_template_from_element_in_edit_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, DelegatingEngine $templating, Response $response)
    {
        $manager->createContext('fsi_admin_crud_edit', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->editAction($element)->shouldReturn($response);
    }

    function it_return_response_from_context_in_edit_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, Response $response)
    {
        $manager->createContext('fsi_admin_crud_edit', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->editAction($element)->shouldReturn($response);
    }

    function it_render_default_template_in_delete_action(Request $request, Response $response, AbstractCRUD $element,
                                                       ContextManager $manager, CreateContext $context, DelegatingEngine $templating)
    {
        $manager->createContext('fsi_admin_crud_delete', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_delete', array(), null)->shouldBeCalled()->willReturn($response);
        $this->deleteAction($element)->shouldReturn($response);
    }

    function it_render_template_from_element_in_delete_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, DelegatingEngine $templating, Response $response)
    {
        $manager->createContext('fsi_admin_crud_delete', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->shouldBeCalled()->willReturn($response);
        $this->deleteAction($element)->shouldReturn($response);
    }

    function it_return_response_from_context_in_delete_action(ContextManager $manager, AbstractCRUD $element,
        CreateContext $context, Request $request, Response $response)
    {
        $manager->createContext('fsi_admin_crud_delete', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->deleteAction($element)->shouldReturn($response);
    }
}
