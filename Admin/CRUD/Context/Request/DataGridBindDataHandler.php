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

use function get_class;
use function sprintf;

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
        if (false === $event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s require ListEvent', get_class($this)));
        }

        if (true === $request->isMethod(Request::METHOD_POST)) {
            $dataGridPreSubmitEvent = ListDataGridPreSubmitRequestEvent::fromOtherEvent($event);
            $this->eventDispatcher->dispatch($dataGridPreSubmitEvent);
            $response = $dataGridPreSubmitEvent->getResponse();
            if (null !== $response) {
                return $response;
            }

            $this->dataGridFormHandler->submit($event->getDataGrid(), $request);

            $dataGridPostSubmitEvent = ListDataGridPostSubmitRequestEvent::fromOtherEvent($event);
            $this->eventDispatcher->dispatch($dataGridPostSubmitEvent);
            $response = $dataGridPostSubmitEvent->getResponse();
            if (null !== $response) {
                return $response;
            }

            if (true === $this->dataGridFormHandler->isValid($event->getDataGrid())) {
                /** @var ListElement $element */
                $element = $event->getElement();
                $element->saveDataGrid();
            }

            $event->getDataSource()->bindParameters($request);
            $event->getDataGrid()->setData($event->getDataSource()->getResult());
        }

        $responsePreRenderEvent = ListResponsePreRenderEvent::fromOtherEvent($event);
        $this->eventDispatcher->dispatch($responsePreRenderEvent);

        return $responsePreRenderEvent->getResponse();
    }
}
