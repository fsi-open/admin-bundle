<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\EventSubscriber;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleSubscriberSpec extends ObjectBehavior
{
    public function let(): void
    {
        $this->beConstructedWith('en');
    }

    public function it_is_event_subscriber(): void
    {
        $this->shouldBeAnInstanceOf(EventSubscriberInterface::class);
    }

    public function it_subscribe_kernel_request_event_before_default_locale_Subscriber(): void
    {
        $this->getSubscribedEvents()->shouldReturn(
            [KernelEvents::REQUEST => [['onKernelRequest', 17]]]
        );
    }

    public function it_do_nothing_when_request_does_not_have_previous_session(
        RequestEvent $event,
        Request $request
    ): void {
        $event->getRequest()->shouldBeCalled()->willReturn($request);
        $request->hasPreviousSession()->shouldBeCalled()->willReturn(false);
        $request->getSession()->shouldNotBeCalled();
        $this->onKernelRequest($event);
    }

    public function it_set_default_locale_if_request_does_not_have_locale_param(
        RequestEvent $event,
        Request $request,
        ParameterBag $requestAttributes,
        SessionInterface $session
    ): void {
        $request->attributes = $requestAttributes;
        $event->getRequest()->willReturn($request);
        $request->hasPreviousSession()->willReturn(true);
        $request->getSession()->willReturn($session);
        $session->get('admin_locale', 'en')->willReturn('de');
        $request->setLocale('de')->shouldBeCalled();

        $this->onKernelRequest($event);
    }

    public function it_does_not_set_locale_if_request_alread_has_locale(
        RequestEvent $event,
        Request $request,
        ParameterBag $requestAttributes,
        SessionInterface $session
    ): void {
        $request->attributes = $requestAttributes;
        $requestAttributes->has('_locale')->willReturn(true);
        $event->getRequest()->willReturn($request);
        $request->hasPreviousSession()->willReturn(true);
        $request->getSession()->willReturn($session);
        $request->setLocale(Argument::any())->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
