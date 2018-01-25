<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\BatchFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\DeleteElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Event\BatchEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\BatchPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        FlashMessages $flashMessage,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        FormInterface $form,
        BatchElement $element,
        FormEvent $event
    ) {
        $requestParameterbag->get('indexes', [])->willReturn(['index']);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $event->getForm()->willReturn($form);
        $event->hasResponse()->willReturn(false);
        $event->getElement()->willReturn($element);
        $form->isValid()->willReturn(true);

        $this->beConstructedWith($eventDispatcher, $router, $flashMessage);
    }

    function it_is_context_request_handler(
    ) {
        $this->shouldHaveType(HandlerInterface::class);
    }

    function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(new RequestHandlerException(
            sprintf("%s requires FormEvent", BatchFormValidRequestHandler::class)
        ))->during('handleRequest', [$listEvent, $request]);
    }

    function it_throw_exception_for_non_redirectable_element(
        FormEvent $formEvent,
        Request $request,
        Element $genericElement
    ) {
        $formEvent->getElement()->willReturn($genericElement);

        $this->shouldThrow(new RequestHandlerException(
            sprintf("%s requires RedirectableElement", BatchFormValidRequestHandler::class)
        ))->during('handleRequest', [$formEvent, $request]);
    }

    function it_handles_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        RouterInterface $router,
        stdClass $object
    ) {
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();
        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_PRE_APPLY,
            Argument::type(BatchPreApplyEvent::class)
        )->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_POST_APPLY,
            Argument::type(BatchEvent::class)
        )->shouldBeCalled();
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn($object);

        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', ['element' => 'element_list_id'])->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(RedirectResponse::class);
    }

    function it_returns_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ) {
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();
        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_PRE_APPLY,
            Argument::type(BatchPreApplyEvent::class)
        )->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_POST_APPLY,
            Argument::type(BatchEvent::class)
        )->shouldBeCalled();
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(['element' => 'element_list_id']);
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn($object);

        $queryParameterbag->has('redirect_uri')->willReturn(true);
        $queryParameterbag->get('redirect_uri')->willReturn('some_redirect_uri');

        $response = $this->handleRequest($event, $request);
        $response->shouldBeAnInstanceOf(RedirectResponse::class);
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    function it_return_response_from_pre_apply_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ) {
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    function it_return_response_from_post_apply_event(
        FormEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ) {
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();
        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_PRE_APPLY,
            Argument::type(BatchPreApplyEvent::class)
        )->shouldBeCalled();

        $form->getData()->willReturn($object);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn($object);

        $element->apply($object)->shouldBeCalled();

        $eventDispatcher->dispatch(
            BatchEvents::BATCH_OBJECT_POST_APPLY,
            Argument::type(BatchEvent::class)
        )->shouldBeCalled();
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_displays_warning_when_no_elements_sent(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        DeleteElement $deleteEelement,
        EventDispatcherInterface $eventDispatcher,
        FlashMessages $flashMessage,
        stdClass $object
    ) {
        $requestParameterbag->get('indexes', [])->willReturn([]);
        $event->getElement()->willReturn($deleteEelement);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $deleteEelement->getOption('allow_delete')->willReturn(true);
        $deleteEelement->hasOption('allow_delete')->willReturn(true);
        $deleteEelement->apply($object)->shouldNotBeCalled();
        $flashMessage->warning(Argument::type('string'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request);
    }
}
