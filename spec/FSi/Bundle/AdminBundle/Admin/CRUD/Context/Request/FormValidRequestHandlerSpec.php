<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\FormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\FormDataPostSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormDataPreSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use stdClass;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        FormEvent $event,
        RouterInterface $router,
        FormElement $element,
        FormInterface $form,
        Request $request
    ): void {
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);
        $event->getForm()->willReturn($form);

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
        $eventDispatcher->dispatch(Argument::type(FormResponsePreRenderEvent::class))->willReturn($event);
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterBag,
        ParameterBag $attributesParameterBag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        RouterInterface $router,
        stdClass $object
    ): void {
        $attributesParameterBag->has('translatableLocale')->willReturn(false);
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $queryParameterBag;
        $request->attributes = $attributesParameterBag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))->willReturn($event);

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(FormDataPostSaveEvent::class))->willReturn($event);

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterBag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_return_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterBag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        FormElement $element,
        stdClass $object
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $queryParameterBag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))->willReturn($event);

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(FormDataPostSaveEvent::class))->willReturn($event);

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $queryParameterBag->has('redirect_uri')->willReturn(true);
        $queryParameterBag->get('redirect_uri')->willReturn('some_redirect_uri');

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

        $eventDispatcher->dispatch(Argument::type(FormResponsePreRenderEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );
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
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );
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
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(FormDataPostSaveEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
