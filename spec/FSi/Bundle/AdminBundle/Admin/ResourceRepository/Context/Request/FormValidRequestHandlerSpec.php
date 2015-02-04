<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\spec\fixtures\Entity\Resource;
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

    function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
                new RequestHandlerException(
                    "FSi\\Bundle\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormValidRequestHandler require FormEvent"
                )
            )->during('handleRequest', array($listEvent, $request));
    }

    function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher
    ) {
        $event->getElement()->willReturn($element);
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element,
        Router $router
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new Resource(), new Resource()));
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'test-resource'));
        $router->generate('fsi_admin_resource', array('element' => 'test-resource'))
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_response_from_pre_render_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_pre_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_entity_save_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new Resource(), new Resource()));
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
