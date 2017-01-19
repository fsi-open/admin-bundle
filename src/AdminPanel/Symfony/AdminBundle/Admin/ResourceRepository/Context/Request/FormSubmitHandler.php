<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractFormSubmitHandler;
use AdminPanel\Symfony\AdminBundle\Event\FormEvents;

class FormSubmitHandler extends AbstractFormSubmitHandler
{
    /**
     * @return string
     */
    protected function getPreSubmitEventName()
    {
        return FormEvents::FORM_REQUEST_PRE_SUBMIT;
    }

    /**
     * @return string
     */
    protected function getPostSubmitEventName()
    {
        return FormEvents::FORM_REQUEST_POST_SUBMIT;
    }
}
