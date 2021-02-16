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
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FSi\Bundle\AdminBundle\Exception\ContextException;

class ListControllerSpec extends ObjectBehavior
{
    public function let(
        ContextManager $manager,
        EngineInterface $templating,
        ListElementContext $context,
        EventDispatcherInterface $dispatcher
    ): void {
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('default_list');

        $this->beConstructedWith($templating, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        ListElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ): void {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type(AdminEvent::class)
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_list', [], null)->willReturn($response);
        $this->listAction($element, $request)->shouldReturn($response);
    }

    public function it_returns_response(
        ContextManager $manager,
        ListElement $element,
        ListElementContext $context,
        Request $request,
        Response $response,
        EngineInterface $templating
    ): void {
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $context->handleRequest($request)->willReturn($response);

        $this->listAction($element, $request)->shouldReturn($response);
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        ListElement $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('my_awesome_list_element');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('listAction', [$element, $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        Request $request,
        ListElement $element,
        ContextManager $manager,
        ListElementContext $context
    ): void {
        $context->hasTemplateName()->willReturn(false);
        $manager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)
            ->during('listAction', [$element, $request]);
    }
}
