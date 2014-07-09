<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

final class FormEvents
{
    const FORM_CONTEXT_POST_CREATE = 'admin.form.context.post_create';

    const FORM_REQUEST_PRE_SUBMIT = 'admin.form.request.pre_submit';

    const FORM_REQUEST_POST_SUBMIT = 'admin.form.request.post_submit';

    const FORM_DATA_PRE_SAVE = 'admin.form.data.pre_save';

    const FORM_DATA_POST_SAVE = 'admin.form.data.post_save';

    const FORM_RESPONSE_PRE_RENDER = 'admin.form.response.pre_render';
}
