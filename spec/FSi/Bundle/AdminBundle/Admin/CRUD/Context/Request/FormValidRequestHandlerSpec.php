<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, FormEvent $event, Router $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', array($listEvent, $request));
    }

    function it_throw_exception_for_non_redirectable_element(FormEvent $formEvent, Request $request)
    {
        $formEvent->getElement()->willReturn(new \stdClass());

        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', array($formEvent, $request));
    }

    function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->getMethod()->willReturn('GET');
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        FormElement $element,
        Router $router
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_list_id'));
        $element->getId()->willReturn('element_form_id');
        $router->generate('fsi_admin_list', array('element' => 'element_list_id'))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_response_from_pre_render_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->getMethod()->willReturn('GET');
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_pre_data_save_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_data_save_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        FormElement $element
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
