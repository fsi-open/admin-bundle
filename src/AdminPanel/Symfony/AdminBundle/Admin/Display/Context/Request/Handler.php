<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Display\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractHandler;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\DisplayEvent;
use AdminPanel\Symfony\AdminBundle\Event\DisplayEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

class Handler extends AbstractHandler
{
    /**
     * @param AdminEvent $event
     * @param Request $request
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        if (!$event instanceof DisplayEvent) {
            throw new RequestHandlerException(sprintf("%s require DisplayEvent", get_class($this)));
        }

        $this->eventDispatcher->dispatch(DisplayEvents::DISPLAY_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }
}
