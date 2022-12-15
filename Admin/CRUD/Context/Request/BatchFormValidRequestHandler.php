<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Event\BatchObjectPostApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectsPostApplyEvent;
use FSi\Bundle\AdminBundle\Event\BatchObjectsPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

use function count;
use function get_class;

class BatchFormValidRequestHandler extends AbstractFormValidRequestHandler
{
    private FlashMessages $flashMessages;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        FlashMessages $flashMessages
    ) {
        parent::__construct($eventDispatcher, $router);

        $this->flashMessages = $flashMessages;
    }

    protected function action(FormEvent $event, Request $request): void
    {
        $element = $event->getElement();
        if (false === $element instanceof BatchElement) {
            throw InvalidArgumentException::create(self::class, BatchElement::class, get_class($element));
        }

        $objects = $this->getObjects($element, $request);

        if (0 === count($objects)) {
            $this->flashMessages->warning('messages.batch.no_elements');
            return;
        }

        foreach ($objects as $object) {
            $preEvent = new BatchObjectPreApplyEvent($element, $request, $object);
            $this->eventDispatcher->dispatch($preEvent);

            if (true === $preEvent->shouldSkip()) {
                continue;
            }

            $element->apply($object);

            $this->eventDispatcher->dispatch(new BatchObjectPostApplyEvent($element, $request, $object));
        }
    }

    protected function getPreSaveEvent(FormEvent $event): FormEvent
    {
        return BatchObjectsPreApplyEvent::fromOtherEvent($event);
    }

    protected function getPostSaveEvent(FormEvent $event): FormEvent
    {
        return BatchObjectsPostApplyEvent::fromOtherEvent($event);
    }

    /**
     * @param BatchElement<array<string, mixed>|object> $element
     * @param Request $request
     * @return array<int|string,array<string,mixed>|object>
     */
    private function getObjects(BatchElement $element, Request $request): array
    {
        $indexes = $request->request->all()['indexes'] ?? [];

        if (0 === count($indexes)) {
            return [];
        }

        return $element->getDataIndexer()->getDataSlice($indexes);
    }
}
