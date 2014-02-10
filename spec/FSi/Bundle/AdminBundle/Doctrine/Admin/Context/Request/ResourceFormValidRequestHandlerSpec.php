<?php

namespace spec\FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Bundle\AdminBundle\Doctrine\Admin\ResourceElement;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceFormValidRequestHandlerSpec extends ObjectBehavior
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

    function it_throw_exception_for_non_list_event(ListEvent $listEvent, Request $request)
    {
        $this->shouldThrow(
                new RequestHandlerException(
                    "FSi\\Bundle\\AdminBundle\\Doctrine\\Admin\\Context\\Request\\ResourceFormValidRequestHandler require FormEvent"
                )
            )->during('handleRequest', array($listEvent, $request));
    }

    function it_do_nothing_on_non_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->getMethod()->willReturn('GET');
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_handle_POST_request(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element,
        Router $router,
        ObjectManager $objectManager
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new \stdClass(), new \stdClass()));
        $event->getElement()->willReturn($element);
        $element->getObjectManager()->willReturn($objectManager);
        $objectManager->persist(Argument::type('stdClass'))->shouldBeCalledTimes(2);
        $objectManager->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getId()->willReturn(1);
        $router->generate('fsi_admin_crud_list', array('element' => 1))->willReturn('/list/page');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_return_response_from_pre_render_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->getMethod()->willReturn('GET');
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_pre_entity_save_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_PRE_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_post_entity_save_event(
        FormEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        Form $form,
        ResourceElement $element,
        ObjectManager $objectManager
    ) {
        $request->getMethod()->willReturn('POST');

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new \stdClass(), new \stdClass()));
        $event->getElement()->willReturn($element);
        $element->getObjectManager()->willReturn($objectManager);
        $objectManager->persist(Argument::type('stdClass'))->shouldBeCalledTimes(2);
        $objectManager->flush()->shouldBeCalled();

        $eventDispatcher->dispatch(ResourceEvents::RESOURCE_POST_SAVE, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
