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

class FormSubmitHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, FormEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormSubmitHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    function it_does_nothing_on_non_POST_request(FormEvent $event, Request $request)
    {
        $request->isMethod('POST')->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_submits_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
