<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
