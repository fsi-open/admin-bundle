<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    protected function action(FormEvent $event, Request $request)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement */
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
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
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
