<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     */
    protected function action(FormEvent $event, Request $request)
    {
        /* @var $element \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\GenericResourceElement */
        $element = $event->getElement();
        $data = $event->getForm()->getData();
        foreach ($data as $resource) {
            $element->save($resource);
        }
    }

    /**
     * @return string
     */
    protected function getPreSaveEventName()
    {
        return FormEvents::FORM_DATA_PRE_SAVE;
    }

    /**
     * @return string
     */
    protected function getPostSaveEventName()
    {
        return FormEvents::FORM_DATA_POST_SAVE;
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response|RedirectResponse
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $response = parent::handleRequest($event, $request);
        if ($response) {
            return $response;
        }

        $this->eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }
}
