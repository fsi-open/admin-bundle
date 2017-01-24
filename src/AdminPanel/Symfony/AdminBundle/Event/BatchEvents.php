<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Event;

final class BatchEvents
{
    const BATCH_REQUEST_PRE_SUBMIT = 'admin.batch.request.pre_submit';

    const BATCH_REQUEST_POST_SUBMIT = 'admin.batch.request.post_submit';

    const BATCH_OBJECTS_PRE_APPLY = 'admin.batch.objects.pre_apply';

    const BATCH_OBJECTS_POST_APPLY = 'admin.batch.objects.post_apply';
}
