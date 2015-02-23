<?php

namespace spec\FSi\Bundle\AdminBundle\EventListener;

use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListenerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('en');
    }

    function it_is_event_subscriber()
    {
        $this->shouldBeAnInstanceOf('Symfony\Component\EventDispatcher\EventSubscriberInterface');
    }

    function it_subscribe_kernel_request_event_before_default_locale_listener()
    {
        $this->getSubscribedEvents()->shouldReturn(
            array(KernelEvents::REQUEST => array(array('onKernelRequest', 17)))
        );
    }

    function it_do_nothing_when_request_does_not_have_previous_session(
        GetResponseEvent $event,
        Request $request
    ) {
        $event->getRequest()->shouldBeCalled()->willReturn($request);
        $request->hasPreviousSession()->shouldBeCalled()->willReturn(false);
        $request->getSession()->shouldNotBeCalled();
        $this->onKernelRequest($event);
    }

    function it_set_default_locale_if_request_does_not_have_locale_param(
        GetResponseEvent $event,
        Request $request,
        Session $session
    ) {
        $event->getRequest()->shouldBeCalled()->willReturn($request);
        $request->hasPreviousSession()->shouldBeCalled()->willReturn(true);
        $request->getSession()->shouldBeCalled()->willReturn($session);
        $session->get('_locale', 'en')->shouldBeCalled()->willReturn('en');
        $request->setLocale('en')->shouldBeCalled();

        $this->onKernelRequest($event);
    }
}
