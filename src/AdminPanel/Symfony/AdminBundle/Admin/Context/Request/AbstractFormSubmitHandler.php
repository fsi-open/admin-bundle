<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormSubmitHandler extends AbstractHandler
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $event = $this->validateEvent($event);

        if (!$request->isMethod('POST')) {
            return;
        }

        $this->eventDispatcher->dispatch($this->getPreSubmitEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $event->getForm()->handleRequest($request);

        $this->eventDispatcher->dispatch($this->getPostSubmitEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\AdminEvent $event
     * @return \AdminPanel\Symfony\AdminBundle\Event\FormEvent
     * @throws \AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf("%s require FormEvent", get_class($this)));
        }

        return $event;
    }

    /**
     * @return string
     */
    abstract protected function getPreSubmitEventName();

    /**
     * @return string
     */
    abstract protected function getPostSubmitEventName();
}
