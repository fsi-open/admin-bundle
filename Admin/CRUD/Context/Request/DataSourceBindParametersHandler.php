<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataSourceBindParametersHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $event = $this->validateEvent($event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event->getDataSource()->bindParameters($request);

        $this->eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
    }

    private function validateEvent(AdminEvent $event): ListEvent
    {
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s requires ListEvent', get_class($this)));
        }

        return $event;
    }
}
