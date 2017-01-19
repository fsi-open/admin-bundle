<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class FormSubmitHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     */
    function let($eventDispatcher, $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $listEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_form_event($listEvent, $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormSubmitHandler require FormEvent"
            )
        )->during('handleRequest', array($listEvent, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_do_nothing_on_non_POST_request($event, $request)
    {
        $request->isMethod('POST')->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     */
    function it_submit_form_on_POST_request($event, $request, $eventDispatcher, $form)
    {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_return_response_from_request_pre_submit_event($event, $request, $eventDispatcher)
    {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     */
    function it_return_response_from_request_post_submit_event($event, $request, $eventDispatcher, $form)
    {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_PRE_SUBMIT, $event)
            ->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_REQUEST_POST_SUBMIT, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
