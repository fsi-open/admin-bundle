<?php

declare(strict_types=1);

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
