<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event;

/**
 * Enum of available events for driver.
 */
class DriverEvents
{
    /**
     * PreGetResult event name.
     */
    const PRE_GET_RESULT = 'datasource_driver.pre_get_result';

    /**
     * PostGetResult event name.
     */
    const POST_GET_RESULT = 'datasource_driver.post_get_result';
}
