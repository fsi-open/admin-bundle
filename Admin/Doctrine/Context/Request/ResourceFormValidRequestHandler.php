<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;

class ResourceFormValidRequestHandler extends AbstractFormValidRequestHandler
{
    /**
     * @return string
     */
    protected function getEntityPreSaveEventName()
    {
        return ResourceEvents::RESOURCE_PRE_SAVE;
    }

    /**
     * @return string
     */
    protected function getEntityPostSaveEventName()
    {
        return ResourceEvents::RESOURCE_POST_SAVE;
    }

    /**
     * @return string
     */
    protected function getResponsePreRenderEventName()
    {
        return ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER;
    }

    /**
     * @param AdminEvent $event
     */
    protected function action(AdminEvent $event)
    {
        if ($event instanceof FormEvent) {
            $data = $event->getForm()->getData();
            foreach ($data as $object) {
                $event->getElement()->getObjectManager()->persist($object);
            }

            $event->getElement()->getObjectManager()->flush();
        }
    }
}