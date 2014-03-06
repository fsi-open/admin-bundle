<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractFormValidRequestHandler extends AbstractFormRequestHandler
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
        if ($request->getMethod() == 'POST') {
            if ($event->getForm()->isValid()) {
                $this->eventDispatcher->dispatch($this->getEntityPreSaveEventName(), $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $this->action($event);
                $this->eventDispatcher->dispatch($this->getEntityPostSaveEventName(), $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return new RedirectResponse(
                    $this->router->generate(
                        $this->getSuccessRouteName(),
                        array('element' => $event->getElement()->getId())
                    )
                );
            }
        }

        $this->eventDispatcher->dispatch($this->getResponsePreRenderEventName(), $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

    /**
     * @param AdminEvent $event
     */
    protected function action(AdminEvent $event)
    {
        $event->getElement()->save($event->getForm()->getData());
    }

    /**
     * @return string
     */
    abstract protected function getSuccessRouteName();

    /**
     * @return string
     */
    abstract protected function getEntityPreSaveEventName();

    /**
     * @return string
     */
    abstract protected function getEntityPostSaveEventName();

    /**
     * @return string
     */
    abstract protected function getResponsePreRenderEventName();
}
