<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping\Event;

use Doctrine\Common\EventArgs;

/**
 * Doctrine event adapter interface is used to retrieve common functionality for Doctrine events.
 */
interface AdapterInterface
{
    /**
     * Set the eventargs
     *
     * @param \Doctrine\Common\EventArgs $args
     */
    function setEventArgs(EventArgs $args);

    /**
     * Get the name of domain object
     *
     * @return string
     */
    function getDomainObjectName();

    /**
     * Get the name of used manager for this event adapter.
     */
    function getManagerName();
}
