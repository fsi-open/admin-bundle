<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata;

interface ClassMetadataInterface
{
    /**
     * Return class name.
     *
     * @return string
     */
    public function getClassName();

    /**
     * Set class name
     *
     * @param string $name
     */
    public function setClassName($name);

    /**
     * Return class reflection object.
     *
     * @return \ReflectionClass
     */
    public function getClassReflection();
}
