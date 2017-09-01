<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormValidRequestHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\DeleteElement;
use FSi\Bundle\AdminBundle\Event\BatchEvent;
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\BatchPreApplyEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Bundle\AdminBundle\Message\FlashMessages;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class BatchFormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @var FlashMessages
     */
    private $flashMessages;

    /**
     * @param EventDispatcherInterface $eventDispatcher
     * @param RouterInterface $router
     * @param FlashMessages|null
     */
    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        RouterInterface $router,
        FlashMessages $flashMessages
    ) {
        parent::__construct($eventDispatcher, $router);
        $this->flashMessages = $flashMessages;
    }

    /**
     * @param FormEvent $event
     * @param Request $request
     */
    protected function action(FormEvent $event, Request $request)
    {
        /** @var BatchElement $element */
        $element = $event->getElement();
        $objects = $this->getObjects($element, $request);

        if (empty($objects)) {
            $this->flashMessages->warning('messages.batch.no_elements');
            return;
        }

        foreach ($objects as $object) {
            $preEvent = $this->eventDispatcher->dispatch(
                BatchEvents::BATCH_OBJECT_PRE_APPLY,
                new BatchPreApplyEvent($element, $request, $object)
            );

            if ($this->shouldSkip($preEvent)) {
                continue;
            }

            $element->apply($object);

            $this->eventDispatcher->dispatch(
                BatchEvents::BATCH_OBJECT_POST_APPLY,
                new BatchEvent($element, $request, $object)
            );
        }
    }

    /**
     * @param BatchElement $element
     * @param Request $request
     * @return array
     * @throws RequestHandlerException
     */
    private function getObjects(BatchElement $element, Request $request)
    {
        $objects = [];
        $indexes = $request->request->get('indexes', []);

        if (!count($indexes)) {
            return [];
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
     * @param Request $request
     * @return bool
     */
    protected function isValidPostRequest(FormEvent $event, Request $request)
    {
        $element = $event->getElement();
        if ($element instanceof DeleteElement
            && $element->hasOption('allow_delete')
            && !$element->getOption('allow_delete')
        ) {
            throw new LogicException(sprintf(
                'Tried to delete objects through element "%s", which has option "allow_delete" set to false',
                get_class($element)
            ));
        }

        return parent::isValidPostRequest($event, $request);
    }

    /**
     * @param FormEvent $event
     * @param Request $request
     * @return RedirectResponse
     */
    protected function getRedirectResponse(FormEvent $event, Request $request)
    {
        if ($request->query->has('redirect_uri')) {
            return new RedirectResponse($request->query->get('redirect_uri'));
        }

        return parent::getRedirectResponse($event, $request);
    }

    /**
     * @return string
     */
    protected function getPreSaveEventName()
    {
        return BatchEvents::BATCH_OBJECTS_PRE_APPLY;
    }

    /**
     * @return string
     */
    protected function getPostSaveEventName()
    {
        return BatchEvents::BATCH_OBJECTS_POST_APPLY;
    }

    /**
     * @param BatchPreApplyEvent $event
     * @return boolean
     */
    private function shouldSkip($event)
    {
        if (!($event instanceof BatchPreApplyEvent)) {
            return false;
        }

        return $event->shouldSkip();
    }
}
