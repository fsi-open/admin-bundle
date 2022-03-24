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
use FSi\Bundle\AdminBundle\Event\ListDataGridPostSetDataEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPreSetDataEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridSetDataHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $listEvent = $this->validateEvent($event);

        $dataGridPreSetDataEvent = ListDataGridPreSetDataEvent::fromOtherEvent($listEvent);
        $this->eventDispatcher->dispatch($dataGridPreSetDataEvent);
        $response = $dataGridPreSetDataEvent->getResponse();
        if (null !== $response) {
            return $response;
        }

        $listEvent->getDataGrid()->setData($listEvent->getDataSource()->getResult());

        $dataGridPostSetDataEvent = ListDataGridPostSetDataEvent::fromOtherEvent($listEvent);
        $this->eventDispatcher->dispatch($dataGridPostSetDataEvent);

        return $dataGridPostSetDataEvent->getResponse();
    }

    private function validateEvent(AdminEvent $event): ListEvent
    {
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s requires ListEvent', get_class($this)));
        }

        return $event;
    }
}
