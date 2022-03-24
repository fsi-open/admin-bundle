<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormRequestPostSubmitEvent;
use FSi\Bundle\AdminBundle\Event\FormRequestPreSubmitEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormSubmitHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        FormEvent $event,
        FormElement $element,
        Request $request
    ): void {
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);

        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormSubmitHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(FormEvent $event, Request $request): void
    {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_submit_form_on_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(FormRequestPreSubmitEvent::class))->shouldBeCalled();

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(FormRequestPostSubmitEvent::class))->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_request_pre_submit_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ): void {
        $event->getForm()->willReturn($form);

        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(FormRequestPreSubmitEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
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
        $eventDispatcher->dispatch(Argument::type(FormRequestPreSubmitEvent::class))->willReturn($event);

        $event->getForm()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(FormRequestPostSubmitEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
