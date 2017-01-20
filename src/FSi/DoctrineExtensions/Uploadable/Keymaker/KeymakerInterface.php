<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\Keymaker;

interface KeymakerInterface
{
    /**
     * Creates key for files.
     *
     * @param object $object
     * @param string $property
     * @param string $id
     * @param string $originalName
     * @param string $pattern
     * @return string
     */
    public function createKey($object, $property, $id, $originalName, $pattern = null);
}
