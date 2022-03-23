<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\Form\FormInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request\FormSubmitHandler;

class FormSubmitHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $eventDispatcher, FormEvent $event): void
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(new RequestHandlerException(sprintf("%s requires FormEvent", FormSubmitHandler::class)))
            ->during('handleRequest', [$listEvent, $request]);
    }

    public function it_does_nothing_on_non_POST_request(FormEvent $event, Request $request): void
    {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_submits_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $eventDispatcher->dispatch($event, FormEvents::FORM_REQUEST_PRE_SUBMIT)->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch($event, FormEvents::FORM_REQUEST_POST_SUBMIT)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $eventDispatcher->dispatch($event, FormEvents::FORM_REQUEST_PRE_SUBMIT)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $eventDispatcher->dispatch($event, FormEvents::FORM_REQUEST_PRE_SUBMIT)->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch($event, FormEvents::FORM_REQUEST_POST_SUBMIT)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
