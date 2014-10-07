<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

class DataGridBindDataHandler extends AbstractHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);

        if ($request->isMethod('POST')) {
            $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getDataGrid()->bindData($request);
            $this->eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getElement()->saveDataGrid();
            $event->getDataSource()->bindParameters($request);
            $event->getDataGrid()->setData($event->getDataSource()->getResult());
        }

        $this->eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

    /**
     * @param AdminEvent $event
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf("%s require ListEvent", get_class($this)));
        }
    }
}
