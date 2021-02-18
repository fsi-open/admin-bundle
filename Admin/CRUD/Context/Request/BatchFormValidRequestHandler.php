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
use FSi\Bundle\AdminBundle\Event\BatchEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\BatchPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\InvalidArgumentException;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

use function count;
use function get_class;

class BatchFormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @var FlashMessages
     */
    private $flashMessages;

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
            $preEvent = new BatchPreApplyEvent($element, $request, $object);
            $this->eventDispatcher->dispatch($preEvent, BatchEvents::BATCH_OBJECT_PRE_APPLY);

            if (true === $preEvent->shouldSkip()) {
                continue;
            }

            $element->apply($object);

            $this->eventDispatcher->dispatch(
                new BatchEvent($element, $request, $object),
                BatchEvents::BATCH_OBJECT_POST_APPLY
            );
        }
    }

    private function getObjects(BatchElement $element, Request $request): array
    {
        $objects = [];
        $indexes = $request->request->get('indexes', []);

        if (false === is_array($indexes) || 0 === count($indexes)) {
            return [];
        }

        foreach ($indexes as $index) {
            $object = $element->getDataIndexer()->getData($index);

            if (null === $object) {
                throw new RequestHandlerException(sprintf("Can't find object with id %s", $index));
            }

            $objects[] = $object;
        }

        return $objects;
    }

    protected function getPreSaveEventName(): string
    {
        return BatchEvents::BATCH_OBJECTS_PRE_APPLY;
    }

    protected function getPostSaveEventName(): string
    {
        return BatchEvents::BATCH_OBJECTS_POST_APPLY;
    }
}
