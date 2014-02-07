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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class FormValidRequestHandler implements HandlerInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface $router
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->router = $router;
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

        if ($request->getMethod() == 'POST') {
            if ($event->getForm()->isValid()) {
                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_ENTITY_PRE_SAVE, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                $event->getElement()->save($event->getForm()->getData());
                $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_ENTITY_POST_SAVE, $event);

                if ($event->hasResponse()) {
                    return $event->getResponse();
                }

                return new RedirectResponse($this->router->generate('fsi_admin_crud_list', array(
                    'element' => $event->getElement()->getId()
                )));
            }
        }

        $this->eventDispatcher->dispatch(CRUDEvents::CRUD_CREATE_RESPONSE_PRE_RENDER, $event);
        if ($event->hasResponse()) {
            return $event->getResponse();
        }
    }

}