<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\DeleteElement;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        FlashMessages $flashMessage,
        FormEvent $event
    ) {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router, $flashMessage);
    }

    function it_is_context_request_handler(
    ) {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throw_exception_for_non_form_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', [$listEvent, $request]);
    }

    function it_throw_exception_for_non_redirectable_element(
        FormEvent $formEvent,
        Request $request,
        stdClass $object
    ) {
        $formEvent->getElement()->willReturn($object);

        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', [$formEvent, $request]);
    }

    function it_handles_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        RouterInterface $router,
        stdClass $object
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

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
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_returns_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

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
        $response->shouldBeAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
        $response->getTargetUrl()->shouldReturn('some_redirect_uri');
    }

    function it_return_response_from_pre_apply_event(
        FormEvent $event,
        BatchElement $element,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form
    ) {
        $request->isMethod('POST')->willReturn(true);
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });
        $event->getElement()->willReturn($element);

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_apply_event(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        EventDispatcherInterface $eventDispatcher,
        FormInterface $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        stdClass $object
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn($object);
        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn($object);

        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_throws_exception_when_delete_not_allowed(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        DeleteElement $element
    ) {
        $request->request = $requestParameterbag;
        $requestParameterbag->get('indexes', [])->willReturn(['index']);
        $event->getElement()->willReturn($element);
        $element->hasOption('allow_delete')->willReturn(true);
        $element->getOption('allow_delete')->willReturn(false);

        $this->shouldThrow('\LogicException')->during('handleRequest', [$event, $request]);
    }

    public function it_displays_warning_when_no_elements_sent(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        FormInterface $form,
        DeleteElement $element,
        EventDispatcherInterface $eventDispatcher,
        FlashMessages $flashMessage
    ) {
        $request->isMethod('POST')->willReturn(true);
        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $requestParameterbag->get('indexes', [])->willReturn([]);
        $request->request = $requestParameterbag;
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $element->getOption('allow_delete')->willReturn(true);
        $element->hasOption('allow_delete')->willReturn(true);
        $element->apply(Argument::type('stdClass'))->shouldNotBeCalled();
        $flashMessage->warning(Argument::type('string'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request);
    }
}
