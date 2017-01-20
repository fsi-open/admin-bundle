<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Util;

/**
 * Attributes container helps to handle attributes.
 */
interface AttributesContainerInterface
{
    /**
     * Checks whether field has attribute with given name.
     *
     * @param string $name
     * @return bool
     */
    public function hasAttribute($name);

    /**
     * Sets attribute.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setAttribute($name, $value);

    /**
     * Returns attribute with given name.
     *
     * @param string $name
     */
    public function getAttribute($name);

    /**
     * Returns array of attributes.
     *
     * @return array
     */
    public function getAttributes();

    /**
     * Removes attribute with given name.
     *
     * @param string $name
     */
    public function removeAttribute($name);
}
