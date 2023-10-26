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
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\BatchFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\DeleteElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\BatchObjectPostApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectsPostApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectsPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use FSi\Component\DataIndexer\DataIndexerInterface;
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

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        FlashMessages $flashMessage,
        Request $request,
        ParameterBag $requestParameterBag,
        ParameterBag $queryParameterBag,
        FormInterface $form,
        BatchElement $element,
        FormEvent $event
    ): void {
        $requestParameterBag->all()->willReturn(['indexes' => ['index']]);
        $request->request = $requestParameterBag;
        $request->query = $queryParameterBag;
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $event->getForm()->willReturn($form);
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);
        $form->isValid()->willReturn(true);

        $this->beConstructedWith($eventDispatcher, $router, $flashMessage);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                sprintf("%s requires FormEvent", BatchFormValidRequestHandler::class)
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
                sprintf("%s requires %s", BatchFormValidRequestHandler::class, RedirectableElement::class)
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    public function it_handles_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterBag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        RouterInterface $router,
        stdClass $object
    ): void {
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPreApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectPreApplyEvent::class))->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(BatchObjectPostApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPostApplyEvent::class))->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getDataSlice(['index'])->willReturn([$object]);

        $queryParameterBag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    public function it_returns_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterBag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ): void {
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPreApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectPreApplyEvent::class))->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(BatchObjectPostApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPostApplyEvent::class))->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getDataSlice(['index'])->willReturn([$object]);

        $queryParameterBag->has('redirect_uri')->willReturn(true);
        $queryParameterBag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    public function it_return_response_from_pre_apply_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPreApplyEvent::class))
            ->will(
                function (array $args): object {
                    $args[0]->setResponse(new Response());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_post_apply_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ): void {
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPreApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectPreApplyEvent::class))->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getDataSlice(['index'])->willReturn([$object]);

        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(BatchObjectPostApplyEvent::class))->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPostApplyEvent::class))
            ->will(
                function (array $args): object {
                    $args[0]->setResponse(new Response());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_displays_warning_when_no_elements_sent(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        DeleteElement $deleteElement,
        EventDispatcherInterface $eventDispatcher,
        FlashMessages $flashMessage,
        DataIndexerInterface $dataIndexer
    ): void {
        $requestParameterBag->all()->willReturn(['indexes' => []]);
        $event->getElement()->willReturn($deleteElement);
        $eventDispatcher->dispatch(Argument::type(BatchObjectsPreApplyEvent::class))->shouldBeCalled();

        $deleteElement->getDataIndexer()->willReturn($dataIndexer);
        $deleteElement->getOption('allow_delete')->willReturn(true);
        $deleteElement->hasOption('allow_delete')->willReturn(true);
        $deleteElement->apply(Argument::any())->shouldNotBeCalled();
        $flashMessage->warning(Argument::type('string'))->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(BatchObjectsPostApplyEvent::class))
            ->will(
                function (array $args): object {
                    $args[0]->setResponse(new Response());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request);
    }
}
