<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Request;

use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormSubmitHandlerSpec extends ObjectBehavior
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
                    "FSi\\Bundle\\AdminBundle\\Doctrine\\Admin\\Context\\Delete\\Request\\FormSubmitHandler require FormEvent"
                )
            )->during('handleRequest', array($listEvent, $request));
    }

    function it_do_nothing_if_request_has_no_confirm(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(false);
        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_submit_form_if_request_has_confirm(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event)
            ->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->submit($request)->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_return_response_from_context_post_create_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event)
            ->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT, $event)
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
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event)
            ->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->submit($request)->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_POST_SUBMIT, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
