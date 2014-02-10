<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractFormSubmitHandler extends AbstractFormRequestHandler
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
        $this->eventDispatcher->dispatch($this->getContextPostCreateEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->getMethod() == 'POST') {
            $this->eventDispatcher->dispatch($this->getFormRequestPreSubmitEventName(), $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getForm()->submit($request);
            $this->eventDispatcher->dispatch($this->getFormRequestPostSubmitEventName(), $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }
        }
    }

    /**
     * @return string
     */
    abstract protected function getContextPostCreateEventName();

    /**
     * @return string
     */
    abstract protected function getFormRequestPreSubmitEventName();

    /**
     * @return string
     */
    abstract protected function getFormRequestPostSubmitEventName();
}