<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use Symfony\Component\HttpFoundation\Request;

class DataGridSetDataHandler extends AbstractListRequestHandler
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

        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event->getDataGrid()->setData($event->getDataSource()->getResult());
        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND, $event);

        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }
} 