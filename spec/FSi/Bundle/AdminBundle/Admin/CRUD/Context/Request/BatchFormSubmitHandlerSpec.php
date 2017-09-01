<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class BatchFormSubmitHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, FormEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormSubmitHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    function it_does_nothing_on_non_POST_request(FormEvent $event, Request $request)
    {
        $request->isMethod('POST')->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_submit_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        FormInterface $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(BatchEvents::BATCH_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_REQUEST_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(BatchEvents::BATCH_REQUEST_PRE_SUBMIT, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_request_post_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        FormInterface $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $eventDispatcher->dispatch(BatchEvents::BATCH_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_REQUEST_POST_SUBMIT, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
