<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Bundle\AdminBundle\Admin\Display\Context\DisplayContext;
use FSi\Bundle\AdminBundle\Admin\Display\Element;
use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DisplayControllerSpec extends ObjectBehavior
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
            'default_display'
        );
    }

    function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        DisplayContext $context,
        EngineInterface $templating
    ) {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_display', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        Element $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('my_awesome_display');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('displayAction', [$element, $request]);
    }

    function it_render_default_template_in_display_action(
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        DisplayContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_display', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    function it_render_template_from_element_in_display_action(
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        DisplayContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $this->displayAction($element, $request)->shouldReturn($response);
    }

    function it_return_response_from_context_in_display_action(
        Request $request,
        Response $response,
        Element $element,
        ContextManager $manager,
        DisplayContext $context
    ) {
        $manager->createContext('fsi_admin_display', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->displayAction($element, $request)->shouldReturn($response);
    }
}
