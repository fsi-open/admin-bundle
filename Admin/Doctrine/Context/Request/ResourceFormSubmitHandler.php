<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\Doctrine\Context\Request;

use FSi\Bundle\AdminBundle\Event\ResourceEvents;

class ResourceFormSubmitHandler extends AbstractFormSubmitHandler
{
    /**
     * @return string
     */
    protected function getContextPostCreateEventName()
    {
        return ResourceEvents::RESOURCE_CONTEXT_POST_CREATE;
    }

    /**
     * @return string
     */
    protected function getFormRequestPreSubmitEventName()
    {
        return ResourceEvents::RESOURCE_FORM_REQUEST_PRE_SUBMIT;
    }

    /**
     * @return string
     */
    protected function getFormRequestPostSubmitEventName()
    {
        return ResourceEvents::RESOURCE_FORM_REQUEST_POST_SUBMIT;
    }
}