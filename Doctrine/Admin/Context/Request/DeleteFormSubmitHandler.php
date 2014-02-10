<?php

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use Symfony\Component\HttpFoundation\Request;

class DeleteFormSubmitHandler extends AbstractFormRequestHandler
{
    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->request->has('confirm')) {
            $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_PRE_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getForm()->submit($request);
            $this->eventDispatcher->dispatch(CRUDEvents::CRUD_DELETE_FORM_POST_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }
        }
    }
}
