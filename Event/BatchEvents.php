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
    public const BATCH_REQUEST_PRE_SUBMIT = BatchRequestPreSubmitEvent::class;

    public const BATCH_REQUEST_POST_SUBMIT = BatchRequestPostSubmitEvent::class;

    public const BATCH_OBJECTS_PRE_APPLY = BatchObjectsPreApplyEvent::class;

    public const BATCH_OBJECTS_POST_APPLY = BatchObjectsPostApplyEvent::class;

    public const BATCH_OBJECT_PRE_APPLY = BatchObjectPreApplyEvent::class;

    public const BATCH_OBJECT_POST_APPLY = BatchObjectPostApplyEvent::class;
}
