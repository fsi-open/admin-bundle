<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Event;

final class PositionableEvents
{
    public const PRE_APPLY = 'admin.positionable.pre_apply';
    public const POST_APPLY = 'admin.positionable.post_apply';
}
