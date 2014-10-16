<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormEvents;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FormValidRequestHandler extends AbstractHandler
{
    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface $router
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($eventDispatcher);
        $this->router = $router;
    }

    /**
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response|RedirectResponse
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        if ($this->isValidPostRequest($event, $request)) {
            $this->eventDispatcher->dispatch(FormEvents::FORM_DATA_PRE_SAVE, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->action($event);

            $this->eventDispatcher->dispatch(FormEvents::FORM_DATA_POST_SAVE, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            return $this->getRedirectResponse($event);
        }

        $this->eventDispatcher->dispatch(FormEvents::FORM_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

    /**
     * @param AdminEvent $event
     * @throws RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf("%s require FormEvent", get_class($this)));
        }
    }

    /**
     * @param FormEvent $event
     * @param Request $request
     * @return bool
     */
    protected function isValidPostRequest(FormEvent $event, Request $request)
    {
        return $request->isMethod('POST') && $event->getForm()->isValid();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function getRedirectResponse(AdminEvent $event)
    {
        /** @var \FSi\Bundle\AdminBundle\Admin\RedirectableElement $element */
        $element = $event->getElement();

        return new RedirectResponse(
            $this->router->generate(
                $element->getSuccessRoute(),
                $element->getSuccessRouteParameters()
            )
        );
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     */
    protected function action(FormEvent $event)
    {
        /* @var $element \FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement */
        $element = $event->getElement();
        $data = $event->getForm()->getData();
        foreach ($data as $resource) {
            $element->save($resource);
        }
    }
}
