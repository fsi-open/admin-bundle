<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, FormEvent $event, RouterInterface $router)
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
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormValidRequestHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    function it_throw_exception_for_non_redirectable_element(
        FormEvent $formEvent,
        Request $request,
        Element $genericElement
    ) {
        $formEvent->getElement()->willReturn($genericElement);

        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormValidRequestHandler requires RedirectableElement"
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        RouterInterface $router,
        stdClass $object
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->query = $queryParameterbag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        stdClass $object
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->query = $queryParameterbag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterbag->has('redirect_uri')->willReturn(true);
        $queryParameterbag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    function it_return_response_from_pre_render_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ) {
        $request->isMethod('POST')->willReturn(false);

        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_pre_data_save_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_data_save_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        stdClass $object,
        Response $response
    ) {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
