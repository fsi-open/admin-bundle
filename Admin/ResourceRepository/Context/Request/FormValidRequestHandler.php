<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormDataPostSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormDataPreSaveEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function get_class;

class FormValidRequestHandler extends AbstractFormValidRequestHandler
{
    public function handleRequest(AdminEvent $event, Request $request): ?Response
    {
        if (false === $event instanceof FormEvent) {
            throw new RequestHandlerException(sprintf('%s requires FormEvent', get_class($this)));
        }

        $response = parent::handleRequest($event, $request);
        if (null !== $response) {
            return $response;
        }

        $responsePreRenderEvent = FormResponsePreRenderEvent::fromOtherEvent($event);
        $this->eventDispatcher->dispatch($responsePreRenderEvent);

        return $responsePreRenderEvent->getResponse();
    }

    protected function action(FormEvent $event, Request $request): void
    {
        $element = $event->getElement();
        if (false === $element instanceof GenericResourceElement) {
            throw InvalidArgumentException::create(self::class, GenericResourceElement::class, get_class($element));
        }

        /* @var $data array<ResourceValue> */
        $data = $event->getForm()->getData();
        foreach ($data as $resource) {
            $element->save($resource);
        }
    }

    protected function getPreSaveEvent(FormEvent $event): FormEvent
    {
        return FormDataPreSaveEvent::fromOtherEvent($event);
    }

    protected function getPostSaveEvent(FormEvent $event): FormEvent
    {
        return FormDataPostSaveEvent::fromOtherEvent($event);
    }
}
