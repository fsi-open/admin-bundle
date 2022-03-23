<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractFormSubmitHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $event = $this->validateEvent($event);

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return null;
        }

        $this->eventDispatcher->dispatch($event, $this->getPreSubmitEventName());
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event->getForm()->handleRequest($request);

        $this->eventDispatcher->dispatch($event, $this->getPostSubmitEventName());
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
    }

    private function validateEvent(AdminEvent $event): FormEvent
    {
        if (false === $event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf('%s requires FormEvent', get_class($this)));
        }

        return $event;
    }

    abstract protected function getPreSubmitEventName(): string;

    abstract protected function getPostSubmitEventName(): string;
}
