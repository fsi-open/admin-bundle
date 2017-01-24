<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Admin\Context\Request\AbstractFormSubmitHandler;
use AdminPanel\Symfony\AdminBundle\Event\BatchEvents;

class BatchFormSubmitHandler extends AbstractFormSubmitHandler
{
    /**
     * @return string
     */
    protected function getPreSubmitEventName()
    {
        return BatchEvents::BATCH_REQUEST_PRE_SUBMIT;
    }

    /**
     * @return string
     */
    protected function getPostSubmitEventName()
    {
        return BatchEvents::BATCH_REQUEST_POST_SUBMIT;
    }
}
