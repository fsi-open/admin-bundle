<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ListControllerSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templating,
        ContextManager $manager,
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith(
            $templating,
            $manager,
            $dispatcher,
            'default_list'
        );
    }

    function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        ListElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ) {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_list', [], null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        ListElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn("my_awesome_list_element");
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('listAction', [$element, $request]);
    }

    function it_render_default_template_in_list_action(
        Request $request,
        Response $response,
        ListElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_list', [], null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_list_action(
        ContextManager $manager,
        ListElement $element,
        ListElementContext $context,
        Request $request,
        EngineInterface $templating,
        Response $response
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_list_action(
        ContextManager $manager,
        ListElement $element,
        ListElementContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->listAction($element, $request)->shouldReturn($response);
    }
}
