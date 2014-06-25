<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Create\Context as CreateContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Context as DeleteContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Read\Context as ReadContext;
use FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Edit\Context as EditContext;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CRUDControllerSpec extends ObjectBehavior
{
    function let(ContextManager $manager, EngineInterface $templating)
    {
        $this->beConstructedWith(
            $templating,
            $manager,
            'default_crud_list',
            'default_crud_create',
            'default_crud_edit',
            'default_crud_delete'
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Controller\CRUDController');
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        AbstractCRUD $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('my_awesome_element');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"my_awesome_element\""))
            ->during('listAction', array($element, $request));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"my_awesome_element\""))
            ->during('createAction', array($element, $request));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"my_awesome_element\""))
            ->during('editAction', array($element, $request));
        $this->shouldThrow(new NotFoundHttpException("Cant find context builder that supports element with id \"my_awesome_element\""))
            ->during('deleteAction', array($element, $request));
    }

    function it_render_default_template_in_list_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        ReadContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_crud_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_list', array(), null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_list_action(
        ContextManager $manager,
        AbstractCRUD $element,
        ReadContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_list_action(
        ContextManager $manager,
        AbstractCRUD $element,
        ReadContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_render_default_template_in_create_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        CreateContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_crud_create', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_create', array(), null)->willReturn($response);
        $this->createAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_create_action(
        ContextManager $manager,
        AbstractCRUD $element,
        CreateContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_create', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->createAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_create_action(
        ContextManager $manager,
        AbstractCRUD $element,
        CreateContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_create', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->createAction($element, $request)->shouldReturn($response);
    }

    function it_render_default_template_in_edit_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        EditContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_crud_edit', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_edit', array(), null)->willReturn($response);
        $this->editAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_edit_action(
        ContextManager $manager,
        AbstractCRUD $element,
        EditContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_edit', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->editAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_edit_action(
        ContextManager $manager,
        AbstractCRUD $element,
        EditContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_edit', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->editAction($element, $request)->shouldReturn($response);
    }

    function it_render_default_template_in_delete_action(
        Request $request,
        Response $response,
        AbstractCRUD $element,
        ContextManager $manager,
        DeleteContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_crud_delete', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_crud_delete', array(), null)->willReturn($response);
        $this->deleteAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_delete_action(
        ContextManager $manager,
        AbstractCRUD $element,
        DeleteContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_delete', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->deleteAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_delete_action(
        ContextManager $manager,
        AbstractCRUD $element,
        DeleteContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_crud_delete', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->deleteAction($element, $request)->shouldReturn($response);
    }
}
