<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BatchFormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, FormEvent $event, Router $router)
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
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', array($listEvent, $request));
    }

    function it_throw_exception_for_non_redirectable_element(FormEvent $formEvent, Request $request)
    {
        $formEvent->getElement()->willReturn(new \stdClass());

        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\BatchFormValidRequestHandler require RedirectableElement"
            )
        )->during('handleRequest', array($formEvent, $request));
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer,
        Router $router
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_list_id'));
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $queryParameterbag->has('redirect_uri')->willReturn(false);
        $router->generate('fsi_admin_list', array('element' => 'element_list_id'))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_redirect_response_with_redirect_uri_passed_by_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterbag,
        ParameterBag $queryParameterbag,
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $request->query = $queryParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_list');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'element_list_id'));
        $element->getId()->willReturn('element_form_id');
        $element->getDataIndexer()->willReturn($dataIndexer);

        $dataIndexer->getData('index')->willReturn(new \stdClass());

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
        EventDispatcher $eventDispatcher,
        Form $form
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
        EventDispatcher $eventDispatcher,
        Form $form,
        BatchElement $element,
        DataIndexerInterface $dataIndexer
    ) {
        $request->isMethod('POST')->willReturn(true);
        $request->request = $requestParameterbag;
        $requestParameterbag->get('indexes', array())->willReturn(array('index'));

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(new \stdClass());
        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData('index')->willReturn(new \stdClass());

        $element->apply(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
