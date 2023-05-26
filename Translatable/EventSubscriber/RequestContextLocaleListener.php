<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\EventSubscriber;

use FSi\Component\Translatable\LocaleProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

final class RequestContextLocaleListener implements EventSubscriberInterface
{
    private LocaleProvider $localeProvider;
    private RequestContext $requestContext;

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 17]]];
    }

    public function __construct(LocaleProvider $localeProvider, RequestContext $requestContext)
    {
        $this->localeProvider = $localeProvider;
        $this->requestContext = $requestContext;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (false === $request->hasPreviousSession()) {
            return;
        }

        $this->requestContext->setParameter(
            'translatableLocale',
            $request->attributes->get('translatableLocale')
        );
    }
}
