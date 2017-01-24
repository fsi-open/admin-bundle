<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Event;

final class FormEvents
{
    const FORM_REQUEST_PRE_SUBMIT = 'admin.form.request.pre_submit';

    const FORM_REQUEST_POST_SUBMIT = 'admin.form.request.post_submit';

    const FORM_DATA_PRE_SAVE = 'admin.form.data.pre_save';

    const FORM_DATA_POST_SAVE = 'admin.form.data.post_save';

    const FORM_RESPONSE_PRE_RENDER = 'admin.form.response.pre_render';
}
