<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\ORM;

final class Events
{
    /**
     * Private constructor. This class is not meant to be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * The postHydrate event occurs for an entity after the entity has been hydrated
     * by the ObjectHydrator (even if it was already loaded before hydration).
     *
     * Note that the postHydrate event occurs for an entity after all associations from the same query
     * have been initialized and loaded. Therefore it is perfectly safe to access associations in
     * a postHydrate callback or event handler.
     *
     * This is an entity lifecycle event.
     *
     * @var string
     */
    const postHydrate = 'postHydrate';
}
