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
    public function let(
        EventDispatcherInterface $eventDispatcher,
        FormEvent $event,
        RouterInterface $router
    ): void {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormValidRequestHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $event->getElement()->willReturn($element);
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch($event, FormEvents::FORM_RESPONSE_PRE_RENDER)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $query,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        RouterInterface $router,
        Resource $resource1,
        Resource $resource2
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $query;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)->shouldBeCalled();

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_POST_SAVE)->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'test-resource']);
        $router->generate('fsi_admin_resource', ['element' => 'test-resource'])
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_return_response_from_pre_render_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch($event, FormEvents::FORM_RESPONSE_PRE_RENDER)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_pre_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_post_entity_save_event(
        FormEvent $event,
        Request $request,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response,
        Resource $resource1,
        Resource $resource2
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)->shouldBeCalled();

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_POST_SAVE)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
