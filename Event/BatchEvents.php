<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

final class BatchEvents
{
    const BATCH_REQUEST_PRE_SUBMIT = 'admin.batch.request.pre_submit';

    const BATCH_REQUEST_POST_SUBMIT = 'admin.batch.request.post_submit';

    const BATCH_OBJECTS_PRE_APPLY = 'admin.batch.objects.pre_apply';

    const BATCH_OBJECTS_POST_APPLY = 'admin.batch.objects.post_apply';

    const BATCH_OBJECT_PRE_APPLY = 'admin.batch.object.pre_apply';

    const BATCH_OBJECT_POST_APPLY = 'admin.batch.object.post_apply';
}
