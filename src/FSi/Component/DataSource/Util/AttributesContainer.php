<?php

declare(strict_types=1);

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
    protected $attributes = [];

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
