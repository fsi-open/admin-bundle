<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

final class FormEvents
{
    public const FORM_REQUEST_PRE_SUBMIT = FormRequestPreSubmitEvent::class;

    public const FORM_REQUEST_POST_SUBMIT = FormRequestPostSubmitEvent::class;

    public const FORM_DATA_PRE_SAVE = FormDataPreSaveEvent::class;

    public const FORM_DATA_POST_SAVE = FormDataPostSaveEvent::class;

    public const FORM_RESPONSE_PRE_RENDER = FormResponsePreRenderEvent::class;
}
