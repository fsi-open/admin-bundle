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
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class FormSubmitHandler implements HandlerInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected $eventDispatcher;

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
        if (!$event instanceof FormEvent) {
            throw new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\Doctrine\\Context\\Request\\FormSubmitHandler require FormEvent"
            );
        }

        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_CONTEXT_POST_CREATE, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        if ($request->getMethod() == 'POST') {
            $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_FORM_REQUEST_PRE_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $event->getForm()->submit($request);
            $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_FORM_REQUEST_POST_SUBMIT, $event);

            if ($event->hasResponse()) {
                return $event->getResponse();
            }
        }
    }
}