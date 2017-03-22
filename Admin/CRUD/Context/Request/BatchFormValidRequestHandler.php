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
use FSi\Bundle\AdminBundle\Event\BatchEvents;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use LogicException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class BatchFormValidRequestHandler extends AbstractFormValidRequestHandler
{
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
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     * @throws \FSi\Bundle\AdminBundle\Exception\ContextBuilderException
     */
    private function getObjects(BatchElement $element, Request $request)
    {
        $objects = [];
        $indexes = $request->request->get('indexes', []);

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
     * @param \FSi\Bundle\AdminBundle\Event\FormEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
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
}
