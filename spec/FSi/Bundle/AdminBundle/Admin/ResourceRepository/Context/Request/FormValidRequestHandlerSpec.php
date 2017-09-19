<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\spec\fixtures\Entity\Resource;
use Symfony\Component\Form\FormInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(
        EventDispatcherInterface $eventDispatcher,
        FormEvent $event,
        RouterInterface $router
    ) {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormValidRequestHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher
    ) {
        $event->getElement()->willReturn($element);
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $query,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        RouterInterface $router,
        Resource $resource1,
        Resource $resource2
    ) {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $query;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'test-resource']);
        $router->generate('fsi_admin_resource', ['element' => 'test-resource'])
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    function it_return_response_from_pre_render_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ) {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_return_response_from_pre_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ) {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_return_response_from_post_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response,
        Resource $resource1,
        Resource $resource2
    ) {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }
}
