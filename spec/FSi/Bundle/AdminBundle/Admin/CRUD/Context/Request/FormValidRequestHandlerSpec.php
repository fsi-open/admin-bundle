<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\FormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
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
use Symfony\Component\HttpFoundation\RedirectResponse;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $eventDispatcher, FormEvent $event, RouterInterface $router): void
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\FormValidRequestHandler requires FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    public function it_throw_exception_for_non_redirectable_element(
        FormEvent $formEvent,
        Request $request,
        Element $genericElement
    ): void {
        $formEvent->getElement()->willReturn($genericElement);

        $this->shouldThrow(
            new RequestHandlerException(
                sprintf("%s requires %s", FormValidRequestHandler::class, RedirectableElement::class)
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    public function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch($event, FormEvents::FORM_RESPONSE_PRE_RENDER)->shouldBeCalled();
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        RouterInterface $router,
        stdClass $object
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $queryParameterbag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_POST_SAVE)->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_return_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        stdClass $object
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $queryParameterbag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_POST_SAVE)->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterbag->has('redirect_uri')->willReturn(true);
        $queryParameterbag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    public function it_return_response_from_pre_render_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);

        $eventDispatcher->dispatch($event, FormEvents::FORM_RESPONSE_PRE_RENDER)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_pre_data_save_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_post_data_save_event(
        FormEvent $event,
        FormElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        stdClass $object,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_PRE_SAVE)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch($event, FormEvents::FORM_DATA_POST_SAVE)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
