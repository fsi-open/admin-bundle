<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\ResourceRepository\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\AbstractFormSubmitHandler;
use FSi\Bundle\AdminBundle\Event\FormEvent;
use FSi\Bundle\AdminBundle\Event\FormRequestPostSubmitEvent;
use FSi\Bundle\AdminBundle\Event\FormRequestPreSubmitEvent;

class FormSubmitHandler extends AbstractFormSubmitHandler
{
    protected function getPreSubmitEvent(FormEvent $event): FormEvent
    {
        return FormRequestPreSubmitEvent::fromOtherEvent($event);
    }

    protected function getPostSubmitEvent(FormEvent $event): FormEvent
    {
        return FormRequestPostSubmitEvent::fromOtherEvent($event);
    }
}
