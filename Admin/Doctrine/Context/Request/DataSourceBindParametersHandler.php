<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class DataSourceBindParametersHandler implements HandlerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param AdminEvent $event
     * @param Request $request
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        if (!$event instanceof ListEvent) {
            throw new RequestHandlerException("DataSourceBindParametersHandler require ListEvent");
        }

        // CRUD_LIST_CONTEXT_POST_CREATE is deprecated and will be removed in version 1.2
        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_LIST_CONTEXT_POST_CREATE, $event);
        if (!$event->hasResponse()) {
            $this->eventDispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND, $event);
            if (!$event->hasResponse()) {
                $event->getDataSource()->bindParameters($request);
                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND, $event);
                if (!$event->hasResponse()) {
                    return ;
                }
            }
        }

        return $event->getResponse();
    }
} 