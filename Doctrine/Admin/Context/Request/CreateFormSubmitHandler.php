<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Doctrine\Admin\Context\Request;

use FSi\Bundle\AdminBundle\Event\CRUDEvents;

class CreateFormSubmitHandler extends AbstractFormSubmitHandler
{
    /**
     * @return string
     */
    protected function getContextPostCreateEventName()
    {
        return CRUDEvents::CRUD_CREATE_CONTEXT_POST_CREATE;
    }

    /**
     * @return string
     */
    protected function getFormRequestPreSubmitEventName()
    {
        return CRUDEvents::CRUD_CREATE_FORM_REQUEST_PRE_SUBMIT;
    }

    /**
     * @return string
     */
    protected function getFormRequestPostSubmitEventName()
    {
        return CRUDEvents::CRUD_CREATE_FORM_REQUEST_POST_SUBMIT;
    }
}