<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Doctrine\Admin\BatchElement;
use FSi\Bundle\AdminBundle\Event\BatchRequestPostSubmitEvent;
use FSi\Bundle\AdminBundle\Event\BatchRequestPreSubmitEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;

class BatchFormSubmitHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcher $eventDispatcher,
        FormEvent $event,
        BatchElement $element,
        Request $request
    ): void {
        $event->getResponse()->willReturn(null);
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);

        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormSubmitHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_does_nothing_on_non_POST_request(FormEvent $event, Request $request): void
    {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_submit_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $eventDispatcher->dispatch(Argument::type(BatchRequestPreSubmitEvent::class))->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);

        $eventDispatcher->dispatch(Argument::type(BatchRequestPostSubmitEvent::class))->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);

        $eventDispatcher->dispatch(Argument::type(BatchRequestPreSubmitEvent::class))
            ->will(function ($args): object {
                $args[0]->setResponse(new Response());

                return $args[0];
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $eventDispatcher->dispatch(Argument::type(BatchRequestPreSubmitEvent::class))->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);

        $eventDispatcher->dispatch(Argument::type(BatchRequestPostSubmitEvent::class))
            ->will(function (array $args): object {
                $args[0]->setResponse(new Response());

                return $args[0];
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
