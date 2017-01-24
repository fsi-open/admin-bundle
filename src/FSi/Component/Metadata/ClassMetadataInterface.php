<?php

declare(strict_types=1);

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
