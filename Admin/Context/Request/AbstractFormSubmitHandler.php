<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function get_class;
use function sprintf;

abstract class AbstractFormSubmitHandler extends AbstractHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf('%s requires FormEvent', get_class($this)));
        }

        if (false === $request->isMethod(Request::METHOD_POST)) {
            return null;
        }

        $preSubmitEvent = $this->getPreSubmitEvent($event);
        $this->eventDispatcher->dispatch($preSubmitEvent);
        $response = $preSubmitEvent->getResponse();
        if (null !== $response) {
            return $response;
        }

        $event->getForm()->handleRequest($request);

        $postSubmitEvent = $this->getPostSubmitEvent($event);
        $this->eventDispatcher->dispatch($postSubmitEvent);

        return $postSubmitEvent->getResponse();
    }

    abstract protected function getPreSubmitEvent(FormEvent $event): FormEvent;

    abstract protected function getPostSubmitEvent(FormEvent $event): FormEvent;
}
