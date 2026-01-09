<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request\FormValidRequestHandler;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Event\FormDataPostSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormDataPreSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\spec\fixtures\Entity\Resource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function class_exists;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        FormEvent $event,
        FormInterface $form,
        ResourceElement $element,
        Request $request,
        RouterInterface $router
    ): void {
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);
        $event->getForm()->willReturn($form);
        $event->getResponse()->willReturn(null);

        $this->beConstructedWith($eventDispatcher, $router);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(sprintf("%s requires FormEvent", FormValidRequestHandler::class))
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
        $eventDispatcher->dispatch(Argument::type(FormResponsePreRenderEvent::class))->willReturn($event);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $attributesParameterBag,
        ResourceElement $element,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        RouterInterface $router,
        Resource $resource1,
        Resource $resource2
    ): void {
        if (class_exists(InputBag::class)) {
            $queryParameterBag = new InputBag();
        } else {
            $queryParameterBag = new ParameterBag();
        }
        $request->query = $queryParameterBag;
        $attributesParameterBag->has('translatableLocale')->willReturn(false);
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $request->query = $queryParameterBag;
        $request->attributes = $attributesParameterBag;

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))->willReturn($event);

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(Argument::type(FormDataPostSaveEvent::class))->willReturn($event);

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'test-resource']);
        $router->generate('fsi_admin_resource', ['element' => 'test-resource'])->willReturn('/resource/test-resource');

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
        $eventDispatcher->dispatch(Argument::type(FormResponsePreRenderEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

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
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

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
        $eventDispatcher->dispatch(Argument::type(FormDataPreSaveEvent::class))->willReturn($event);

        $form->getData()->willReturn([$resource1, $resource2]);
        $event->getElement()->willReturn($element);
        $element->save(Argument::type(Resource::class))->shouldBeCalledTimes(2);

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
