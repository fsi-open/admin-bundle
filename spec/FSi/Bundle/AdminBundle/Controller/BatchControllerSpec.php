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
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchControllerSpec extends ObjectBehavior
{
    function let(
        EngineInterface $templating,
        ContextManager $manager,
        EventDispatcherInterface $dispatcher
    ) {
        $this->beConstructedWith($templating, $manager, $dispatcher);
    }

    function it_dispatches_event(
        EventDispatcherInterface $dispatcher,
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ) {
        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }

    function it_throws_exception_when_cant_find_context_builder_that_supports_admin_element(
        BatchElement $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('admin_element_id');
        $manager->createContext(Argument::type('string'), $element)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('batchAction', [$element, $request]);
    }

    function it_throws_exception_when_context_does_not_return_response(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->hasTemplateName()->willReturn(false);
        $context->handleRequest($request)->willReturn(null);

        $this->shouldThrow('FSi\Bundle\AdminBundle\Exception\ContextException')
            ->during('batchAction', [$element, $request]);
    }

    function it_return_response_from_context_in_batch_action(
        ContextManager $manager,
        BatchElement $element,
        BatchElementContext $context,
        Request $request,
        Response $response
    ) {
        $manager->createContext('fsi_admin_batch', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->batchAction($element, $request)->shouldReturn($response);
    }
}
