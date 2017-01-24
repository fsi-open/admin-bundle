<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvent;
use AdminPanel\Symfony\AdminBundle\Event\FormEvent;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractFormValidRequestHandler extends AbstractHandler
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($eventDispatcher);
        $this->router = $router;
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $event = $this->validateEvent($event);
        if (!$this->isValidPostRequest($event, $request)) {
            return;
        }

        $this->eventDispatcher->dispatch($this->getPreSaveEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->action($event, $request);

        $this->eventDispatcher->dispatch($this->getPostSaveEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return $this->getRedirectResponse($event, $request);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return bool
     */
    protected function isValidPostRequest(FormEvent $event, Request $request)
    {
        return $request->isMethod('POST') && $event->getForm()->isValid();
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
        if (!$event->getElement() instanceof RedirectableElement) {
            throw new RequestHandlerException(sprintf("%s require RedirectableElement", get_class($this)));
        }

        return $event;
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getRedirectResponse(FormEvent $event, Request $request)
    {
        /** @var \AdminPanel\Symfony\AdminBundle\Admin\RedirectableElement $element */
        $element = $event->getElement();

        return new RedirectResponse(
            $this->router->generate(
                $element->getSuccessRoute(),
                $element->getSuccessRouteParameters()
            )
        );
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    abstract protected function action(FormEvent $event, Request $request);

    /**
     * @return string
     */
    abstract protected function getPreSaveEventName();

    /**
     * @return string
     */
    abstract protected function getPostSaveEventName();
}
