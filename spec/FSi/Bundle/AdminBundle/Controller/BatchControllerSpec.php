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
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext;
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

class BatchControllerSpec extends ObjectBehavior
{
    public function let(
        ManagerInterface $elementManager,
        Environment $twig,
        ContextManager $contextManager,
        EventDispatcherInterface $dispatcher,
        BatchElement $element
    ): void {
        $elementManager->hasElement('admin_element_id')->willReturn(true);
        $elementManager->getElement('admin_element_id')->willReturn($element);
        $this->beConstructedWith($elementManager, $twig, $contextManager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        ContextManager $contextManager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_batch', $element)->shouldBeCalled()->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction('admin_element_id', $request)->shouldReturn($response);
    }

    public function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        EventDispatcherInterface $dispatcher,
        BatchElement $element,
        ContextManager $contextManager,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $element->getId()->willReturn('admin_element_id');
        $contextManager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)->during('batchAction', ['admin_element_id', $request]);
    }

    public function it_throws_exception_when_context_does_not_return_response(
        EventDispatcherInterface $dispatcher,
        ContextManager $contextManager,
        BatchElement $element,
        BatchElementContext $context,
        AdminEvent $event,
        Request $request
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->getTemplateName()->willReturn(null);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)->during('batchAction', ['admin_element_id', $request]);
    }

    public function it_returns_response_from_context_in_batch_action(
        EventDispatcherInterface $dispatcher,
        ContextManager $contextManager,
        BatchElement $element,
        BatchElementContext $context,
        AdminEvent $event,
        Request $request,
        Response $response
    ): void {
        $dispatcher->dispatch(Argument::type(AdminContextPreCreateEvent::class))->shouldBeCalled();

        $contextManager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction('admin_element_id', $request)->shouldReturn($response);
    }
}
