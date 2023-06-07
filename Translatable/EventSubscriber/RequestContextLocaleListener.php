<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Translatable\EventSubscriber;

use FSi\Bundle\AdminBundle\Request\Parameters;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContext;

final class RequestContextLocaleListener implements EventSubscriberInterface
{
    private RequestContext $requestContext;

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 17]]];
    }

    public function __construct(RequestContext $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (false === $request->hasPreviousSession()) {
            return;
        }

        if (false === $request->attributes->has(Parameters::TRANSLATABLE_LOCALE)) {
            return;
        }

        $this->requestContext->setParameter(
            Parameters::TRANSLATABLE_LOCALE,
            $request->attributes->get(Parameters::TRANSLATABLE_LOCALE)
        );
    }
}
