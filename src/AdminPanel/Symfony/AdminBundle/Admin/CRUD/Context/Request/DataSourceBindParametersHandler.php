<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractHandler;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvent;
use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

class DataSourceBindParametersHandler extends AbstractHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);

        if (!$event->hasResponse()) {
            $this->eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event);
            if (!$event->hasResponse()) {
                $event->getDataSource()->bindParameters($request);
                $this->eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event);
                if (!$event->hasResponse()) {
                    return;
                }
            }
        }

        return $event->getResponse();
    }

    /**
     * @param AdminEvent $event
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof ListEvent) {
            throw new RequestHandlerException(sprintf("%s require ListEvent", get_class($this)));
        }
    }
}
