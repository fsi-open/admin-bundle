<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use AdminPanel\Symfony\AdminBundle\Tests\Doubles\Entity\Resource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class FormValidRequestHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    function let($eventDispatcher, $event, $router)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher, $router);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $listEvent
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_list_event($listEvent, $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\ResourceRepository\\Context\\Request\\FormValidRequestHandler require FormEvent"
            )
        )->during('handleRequest', array($listEvent, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_do_nothing_on_non_POST_request($event, $request, $element, $eventDispatcher)
    {
        $event->getElement()->willReturn($element);
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */
    function it_handle_POST_request($event, $request, $eventDispatcher, $form, $element, $router)
    {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new Resource(), new Resource()));
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->shouldBeCalled();

        $element->getSuccessRoute()->willReturn('fsi_admin_resource');
        $element->getSuccessRouteParameters()->willReturn(array('element' => 'test-resource'));
        $router->generate('fsi_admin_resource', array('element' => 'test-resource'))
            ->willReturn('/resource/test-resource');

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_return_response_from_pre_render_event($event, $request, $element, $eventDispatcher)
    {
        $request->isMethod('POST')->willReturn(false);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     */
    function it_return_response_from_pre_entity_save_event($event, $request, $element, $eventDispatcher, $form)
    {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $event->getElement()->willReturn($element);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \Symfony\Component\Form\Form $form
     * @param \AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement $element
     */
    function it_return_response_from_post_entity_save_event($event, $request, $eventDispatcher, $form, $element)
    {
        $request->isMethod('POST')->willReturn(true);

        $event->getForm()->willReturn($form);
        $form->isValid()->willReturn(true);
        $eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event)
            ->shouldBeCalled();

        $form->getData()->willReturn(array(new Resource(), new Resource()));
        $event->getElement()->willReturn($element);
        $element->save(Argument::type('AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Entity\\Resource'))->shouldBeCalledTimes(2);

        $eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
