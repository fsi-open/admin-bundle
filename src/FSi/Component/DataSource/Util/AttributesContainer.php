<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Util;

/**
 * {@inheritdoc}
 */
class AttributesContainer implements AttributesContainerInterface
{
    /**
     * Attributes.
     *
     * @var array
     */
    protected $attributes = array();

    /**
     * {@inheritdoc}
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * {@inheritdoc}
     */
    public function setAttribute($name, $value)
    {
        if (isset($value)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttribute($name)
    {
        if ($this->hasAttribute($name)) {
            return $this->attributes[$name];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function removeAttribute($name)
    {
        unset($this->attributes[$name]);
    }
}
