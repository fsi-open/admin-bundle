<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Event;

/**
 * Enum of available events for field.
 */
class FieldEvents
{
    /**
     * PreBindParameters event name.
     */
    const PRE_BIND_PARAMETER = 'datasource_field.pre_bind_parameter';

    /**
     * PostBindParameters event name.
     */
    const POST_BIND_PARAMETER = 'datasource_field.post_bind_parameter';

    /**
     * PostGetParameter event name.
     */
    const POST_GET_PARAMETER = 'datasource_field.post_get_parameter';

    /**
     * PostBuildView event name.
     */
    const POST_BUILD_VIEW = 'datasource_field.post_build_view';
}
