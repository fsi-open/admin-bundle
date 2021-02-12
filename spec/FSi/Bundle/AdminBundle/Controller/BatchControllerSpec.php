<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Controller;

use FSi\Bundle\AdminBundle\Admin\Context\ContextManager;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\BatchElementContext;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FSi\Bundle\AdminBundle\Exception\ContextException;
use Twig\Environment;

class BatchControllerSpec extends ObjectBehavior
{
    function let(
        Environment $twig,
        ContextManager $manager,
        EventDispatcherInterface $dispatcher
    ): void {
        $this->beConstructedWith($templating, $manager, $dispatcher);
    }

    public function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ): void {
        $dispatcher->dispatch(
            Argument::type(AdminEvent::class),
            AdminEvents::CONTEXT_PRE_CREATE
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }

    public function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        BatchElement $element,
        ContextManager $manager,
        Request $request
    ): void {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(NotFoundHttpException::class)
            ->during('batchAction', [$element, $request]);
    }

    public function it_throws_exception_when_context_does_not_return_response(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request
    ): void {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->hasTemplateName()->willReturn(false);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow(ContextException::class)
            ->during('batchAction', [$element, $request]);
    }

    public function it_returns_response_from_context_in_batch_action(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ): void {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
