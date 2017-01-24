<?php

declare(strict_types=1);

namespace FSi\Component\DataSource;

/**
 * Extension of DataSource.
 */
interface DataSourceExtensionInterface
{
    /**
     * Loads events subscribers.
     *
     * Each subscriber must implements Symfony\Component\EventDispatcher\EventSubscriberInterface.
     *
     * @return array
     */
    public function loadSubscribers();

    /**
     * Allows DataSource extension to load extensions directly to its driver.
     *
     * @return array
     */
    public function loadDriverExtensions();
}
