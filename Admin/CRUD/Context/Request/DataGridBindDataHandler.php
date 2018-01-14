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
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\EventSubscriber\FormSubscriber;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandler extends AbstractHandler
{
    /**
     * @var FormSubscriber
     */
    private $formSubscriber;

    public function __construct(EventDispatcherInterface $eventDispatcher, FormSubscriber $formSubscriber)
    {
        parent::__construct($eventDispatcher);

        $this->formSubscriber = $formSubscriber;
    }

    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $event = $this->validateEvent($event);

        if ($request->isMethod(Request::METHOD_POST)) {
            $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $submitted = $this->formSubscriber->handleDataGridForm($event->getDataGrid(), $request);

            $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            if ($submitted) {
                $event->getElement()->saveDataGrid();
            }
            $event->getDataSource()->bindParameters($request);
            $event->getDataGrid()->setData($event->getDataSource()->getResult());
        }

        $this->eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return null;
    }

    private function validateEvent(AdminEvent $event): ListEvent
    {
        if (!$event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf('%s require ListEvent', get_class($this)));
        }

        return $event;
    }
}
