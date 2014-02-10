<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Delete\Request;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataIndexer\DataIndexerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, FormEvent $event, Router $router)
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
                    "FSi\\Bundle\\AdminBundle\\Doctrine\\Admin\\Context\\Delete\\Request\\FormValidRequestHandler require FormEvent"
                )
            )->during('handleRequest', array($listEvent, $request));
    }

    function it_do_nothing_if_request_has_no_confirm(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(false);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_should_throw_exception_when_there_are_no_indexes_in_request(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form,
        CRUDElement $element
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->shouldBeCalled();

        $event->getElement()->willReturn($element);
        $requestParameterBag->get('indexes', array())->willReturn(array());

        $this->shouldThrow(new RequestHandlerException("There must be at least one object to execute delete action"))
            ->during('handleRequest', array($event, $request));
    }

    function it_throws_exception_for_non_existing_entity(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form,
        CRUDElement $element,
        DataIndexerInterface $dataIndexer
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->shouldBeCalled();

        $requestParameterBag->get('indexes', array())->willReturn(array(1, 2));

        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $entity = new \stdClass();
        $dataIndexer->getData(1)->shouldBeCalled()->willReturn($entity);
        $dataIndexer->getData(2)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(
            new RequestHandlerException("Can't find object with id 2")
        )->during('handleRequest', array($event, $request));
    }

    function it_handle_request_if_confirmed(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form,
        CRUDElement $element,
        DataIndexerInterface $dataIndexer,
        Router $router
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->shouldBeCalled();

        $requestParameterBag->get('indexes', array())->willReturn(array(1, 2, 3));

        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData(1)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(2)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(3)->shouldBeCalled()->willReturn(new \stdClass());
        $element->delete(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event)
            ->shouldBeCalled();

        $element->getId()->willReturn(1);
        $router->generate('fsi_admin_crud_list', array('element' => 1))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_response_from_pre_entity_delete_event(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_entity_delete_event(
        FormEvent $event,
        Request $request,
        ParameterBag $requestParameterBag,
        EventDispatcher $eventDispatcher,
        Form $form,
        CRUDElement $element,
        DataIndexerInterface $dataIndexer
    ) {
        $request->request = $requestParameterBag;
        $requestParameterBag->has('confirm')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_PRE_DELETE, $event)
            ->shouldBeCalled();

        $requestParameterBag->get('indexes', array())->willReturn(array(1, 2, 3));

        $event->getElement()->willReturn($element);
        $element->getDataIndexer()->willReturn($dataIndexer);
        $dataIndexer->getData(1)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(2)->shouldBeCalled()->willReturn(new \stdClass());
        $dataIndexer->getData(3)->shouldBeCalled()->willReturn(new \stdClass());
        $element->delete(Argument::type('stdClass'))->shouldBeCalled();

        $eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_ENTITIES_POST_DELETE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
