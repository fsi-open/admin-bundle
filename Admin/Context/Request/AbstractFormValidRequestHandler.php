<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractFormValidRequestHandler extends AbstractHandler
{
    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($eventDispatcher);
        $this->router = $router;
    }

    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        $event = $this->validateEvent($event);
        if (false === $this->isValidPostRequest($event, $request)) {
            return null;
        }

        $this->eventDispatcher->dispatch($event, $this->getPreSaveEventName());
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        $this->action($event, $request);

        $this->eventDispatcher->dispatch($event, $this->getPostSaveEventName());
        if ($event->hasResponse()) {
            return $event->getResponse();
        }

        return $this->getRedirectResponse($event, $request);
    }

    protected function isValidPostRequest(FormEvent $event, Request $request): bool
    {
        return true === $request->isMethod(Request::METHOD_POST) && true === $event->getForm()->isValid();
    }

    protected function getRedirectResponse(FormEvent $event, Request $request): RedirectResponse
    {
        if (true === $request->query->has('redirect_uri')) {
            return new RedirectResponse($request->query->get('redirect_uri'));
        }

        $element = $this->validateElement($event->getElement());

        return new RedirectResponse(
            $this->router->generate($element->getSuccessRoute(), $element->getSuccessRouteParameters())
        );
    }

    abstract protected function action(FormEvent $event, Request $request): void;

    abstract protected function getPreSaveEventName(): string;

    abstract protected function getPostSaveEventName(): string;

    private function validateEvent(AdminEvent $event): FormEvent
    {
        if (false === $event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf('%s requires FormEvent', get_class($this)));
        }

        $this->validateElement($event->getElement());

        return $event;
    }

    private function validateElement(Element $element): RedirectableElement
    {
        if (false === $element instanceof RedirectableElement) {
            throw new RequestHandlerException(sprintf('%s requires %s', get_class($this), RedirectableElement::class));
        }

        return $element;
    }
}
