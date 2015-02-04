<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormSubmitHandler extends AbstractHandler
{
    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
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

        $event->getForm()->submit($request);

        $this->eventDispatcher->dispatch($this->getPostSubmitEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @return \FSi\Bundle\AdminBundle\Event\FormEvent
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
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
