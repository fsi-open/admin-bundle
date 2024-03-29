<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Display\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\DisplayEvent;
use FSi\Bundle\AdminBundle\Event\DisplayResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Handler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof DisplayEvent) {
            throw new RequestHandlerException(sprintf('%s requires DisplayEvent', get_class($this)));
        }

        $responsePreRenderEvent = DisplayResponsePreRenderEvent::fromOtherEvent($event);
        $this->eventDispatcher->dispatch($responsePreRenderEvent);

        return $responsePreRenderEvent->getResponse();
    }
}
