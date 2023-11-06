<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Admin\RedirectableElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function get_class;
use function is_string;
use function sprintf;

abstract class AbstractFormValidRequestHandler extends AbstractHandler
{
    protected RouterInterface $router;

    public function __construct(EventDispatcherInterface $eventDispatcher, RouterInterface $router)
    {
        parent::__construct($eventDispatcher);

        $this->router = $router;
    }

    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf('%s requires FormEvent', get_class($this)));
        }
        $element = $event->getElement();
        if (false === $element instanceof RedirectableElement) {
            throw new RequestHandlerException(sprintf('%s requires %s', get_class($this), RedirectableElement::class));
        }

        if (false === $this->isValidPostRequest($event, $request)) {
            return null;
        }

        $preSaveEvent = $this->getPreSaveEvent($event);
        $this->eventDispatcher->dispatch($preSaveEvent);
        $response = $preSaveEvent->getResponse();
        if (null !== $response) {
            return $response;
        }

        $this->action($event, $request);
        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $postSaveEvent = $this->getPostSaveEvent($event);
        $this->eventDispatcher->dispatch($postSaveEvent);
        $response = $postSaveEvent->getResponse();

        return $response ?? $this->getRedirectResponse($element, $request);
    }

    protected function isValidPostRequest(FormEvent $event, Request $request): bool
    {
        return true === $request->isMethod(Request::METHOD_POST) && true === $event->getForm()->isValid();
    }

    protected function getRedirectResponse(RedirectableElement $element, Request $request): RedirectResponse
    {
        if (true === $request->query->has('redirect_uri')) {
            $redirectUri = $request->query->get('redirect_uri');
            if (false === is_string($redirectUri)) {
                throw new LogicException(
                    sprintf('Query parameter redirect_uri must be a string, "%s" given.', gettype($redirectUri))
                );
            }

            return new RedirectResponse($redirectUri);
        }

        return new RedirectResponse(
            $this->router->generate($element->getSuccessRoute(), $element->getSuccessRouteParameters())
        );
    }

    abstract protected function action(FormEvent $event, Request $request): void;

    abstract protected function getPreSaveEvent(FormEvent $event): FormEvent;

    abstract protected function getPostSaveEvent(FormEvent $event): FormEvent;
}
