<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\ListElementContext;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Event\AdminContextPreCreateEvent;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Twig\Environment;

class ListControllerSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $elementManager,
        ContextManager $contextManager,
        Environment $twig,
        ListElementContext $context,
        EventDispatcherInterface $dispatcher,
        ListElement $element
    ): void {
        $elementManager->hasElement('admin_element_id')->willReturn(true);
        $elementManager->getElement('admin_element_id')->willReturn($element);
        $context->getTemplateName()->willReturn('default_list');

        $this->beConstructedWith($elementManager, $twig, $contextManager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        Request $request,
        ListElement $element,
        ContextManager $contextManager,
        ListElementContext $context,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('default_list', [])->willReturn('response');
        $this->listAction('admin_element_id', $request)->getContent()->shouldBe('response');
    }

    public function it_returns_response(
        EventDispatcherInterface $dispatcher,
        AdminEvent $event,
        ContextManager $contextManager,
        ListElement $element,
        ListElementContext $context,
        Request $request,
        Environment $twig
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->getData()->willReturn([]);

        $twig->render('custom_template', [])->willReturn('response');
        $response = new Response('response');
        $context->handleRequest($request)->willReturn($response);

        $this->listAction('admin_element_id', $request)->shouldReturn($response);
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        EventDispatcherInterface $dispatcher,
        AdminEvent $event,
        ListElement $element,
        ContextManager $contextManager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('my_awesome_list_element');
        $contextManager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)->during('listAction', ['admin_element_id', $request]);
    }

    public function it_throws_exception_when_no_response_and_no_template_name(
        EventDispatcherInterface $dispatcher,
        AdminEvent $event,
        Request $request,
        ListElement $element,
        ContextManager $contextManager,
        ListElementContext $context
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $context->getTemplateName()->willReturn(null);
        $contextManager->createContext('fsi_admin_list', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)->during('listAction', ['admin_element_id', $request]);
    }
}
