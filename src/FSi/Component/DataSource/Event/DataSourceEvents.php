<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Event;

/**
 * Enum of available events.
 */
class DataSourceEvents
{
    /**
     * PreBindParameters event name.
     */
    const PRE_BIND_PARAMETERS = 'datasource.pre_bind_parameters';

    /**
     * PostBindParameters event name.
     */
    const POST_BIND_PARAMETERS = 'datasource.post_bind_parameters';

    /**
     * PreGetResult event name.
     */
    const PRE_GET_RESULT = 'datasource.pre_get_result';

    /**
     * PostGetResult event name.
     */
    const POST_GET_RESULT = 'datasource.post_get_result';

    /**
     * PreBuildView event name.
     */
    const PRE_BUILD_VIEW = 'datasource.pre_build_view';

    /**
     * PostBuildView event name.
     */
    const POST_BUILD_VIEW = 'datasource.post_build_view';

    /**
     * PreGetParameters event name.
     */
    const PRE_GET_PARAMETERS = 'datasource.pre_get_parameters';

    /**
     * PostGetParameters event name.
     */
    const POST_GET_PARAMETERS = 'datasource.post_get_parameters';
}
