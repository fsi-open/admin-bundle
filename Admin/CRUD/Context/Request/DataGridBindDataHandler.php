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
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPostSubmitRequestEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPreSubmitRequestEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGridFormHandlerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandler extends AbstractHandler
{
    private DataGridFormHandlerInterface $dataGridFormHandler;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        DataGridFormHandlerInterface $dataGridFormHandler
    ) {
        parent::__construct($eventDispatcher);

        $this->dataGridFormHandler = $dataGridFormHandler;
    }

    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $listEvent = $this->validateEvent($event);

        if (true === $request->isMethod(Request::METHOD_POST)) {
            $dataGridPreSubmitEvent = ListDataGridPreSubmitRequestEvent::fromOtherEvent($listEvent);
            $this->eventDispatcher->dispatch($dataGridPreSubmitEvent);
            $response = $dataGridPreSubmitEvent->getResponse();
            if (null !== $response) {
                return $response;
            }

            $this->dataGridFormHandler->submit($listEvent->getDataGrid(), $request);

            $dataGridPostSubmitEvent = ListDataGridPostSubmitRequestEvent::fromOtherEvent($listEvent);
            $this->eventDispatcher->dispatch($dataGridPostSubmitEvent);
            $response = $dataGridPostSubmitEvent->getResponse();
            if (null !== $response) {
                return $response;
            }

            if (true === $this->dataGridFormHandler->isValid($listEvent->getDataGrid())) {
                /** @var ListElement $element */
                $element = $listEvent->getElement();
                $element->saveDataGrid();
            }

            $listEvent->getDataSource()->bindParameters($request);
            $listEvent->getDataGrid()->setData($listEvent->getDataSource()->getResult());
        }

        $responsePreRenderEvent = ListResponsePreRenderEvent::fromOtherEvent($listEvent);
        $this->eventDispatcher->dispatch($responsePreRenderEvent);

        return $responsePreRenderEvent->getResponse();
    }

    private function validateEvent(AdminEvent $event): ListEvent
    {
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s require ListEvent', get_class($this)));
        }

        return $event;
    }
}
