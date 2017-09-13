<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormSubmitHandler;
use FSi\Bundle\AdminBundle\Event\FormEvents;

class FormSubmitHandler extends AbstractFormSubmitHandler
{
    protected function getPreSubmitEventName(): string
    {
        return FormEvents::FORM_REQUEST_PRE_SUBMIT;
    }

    protected function getPostSubmitEventName(): string
    {
        return FormEvents::FORM_REQUEST_POST_SUBMIT;
    }
}
