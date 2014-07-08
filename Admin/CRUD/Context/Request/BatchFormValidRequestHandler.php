<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class BatchFormValidRequestHandler extends AbstractHandler
{
    /**
     * @var RouterInterface
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
     * @param AdminEvent $event
     * @param Request $request
     * @return null|\Symfony\Component\HttpFoundation\Response|RedirectResponse
     */
    public function handleRequest(AdminEvent $event, Request $request)
    {
        $this->validateEvent($event);
        if ($this->isValidPostRequest($event, $request)) {
            $this->eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_PRE_APPLY, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            $this->action($event, $request);

            $this->eventDispatcher->dispatch(BatchEvents::BATCH_OBJECTS_POST_APPLY, $event);
            if ($event->hasResponse()) {
                return $event->getResponse();
            }

            return $this->getRedirectResponse($event);
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
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    protected function action(FormEvent $event, Request $request)
    {
        /** @var \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element */
        $element = $event->getElement();
        $objects = $this->getObjects($element, $request);

        foreach ($objects as $object) {
            $element->apply($object);
        }
    }

    /**
     * @param AdminEvent $event
     * @throws \FSi\Bundle\AdminBundle\Exception\RequestHandlerException
     */
    protected function validateEvent(AdminEvent $event)
    {
        if (!$event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf("%s require FormEvent", get_class($this)));
        }
        if (!$event->getElement() instanceof RedirectableElement) {
            throw new RequestHandlerException(sprintf("%s require RedirectableElement", get_class($this)));
        }
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws \FSi\Bundle\AdminBundle\Exception\ContextBuilderException
     */
    protected function getObjects(BatchElement $element, Request $request)
    {
        $objects = array();
        $indexes = $request->request->get('indexes', array());

        if (!count($indexes)) {
            throw new RequestHandlerException('There must be at least one object to execute batch action');
        }

        foreach ($indexes as $index) {
            $object = $element->getDataIndexer()->getData($index);

            if (!isset($object)) {
                throw new RequestHandlerException(sprintf("Can't find object with id %s", $index));
            }

            $objects[] = $object;
        }

        return $objects;
    }

    /**
     * @param FormEvent $event
     * @return RedirectResponse
     */
    protected function getRedirectResponse(FormEvent $event)
    {
        /** @var \FSi\Bundle\AdminBundle\Admin\CRUD\RedirectableElement $element */
        $element = $event->getElement();

        return new RedirectResponse(
            $this->router->generate(
                $element->getSuccessRoute(),
                $element->getSuccessRouteParameters()
            )
        );
    }
}
