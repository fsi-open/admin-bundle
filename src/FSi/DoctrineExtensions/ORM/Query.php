<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\ORM;

final class Query
{
    /**
     * Private constructor. This class is not meant to be instantiated.
     */
    private function __construct()
    {
    }

    /**
     * Hydrates an object graph. It uses custom hydrator to dispatch postHydrate events.
     */
    const HYDRATE_OBJECT = 6;
}
