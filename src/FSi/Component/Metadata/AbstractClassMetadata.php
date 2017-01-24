<?php

declare(strict_types=1);

namespace FSi\Component\Metadata;

abstract class AbstractClassMetadata implements ClassMetadataInterface
{
    /**
     * Name of the class for which the data is stored
     *
     * @var string
     */
    protected $class;

    /**
     * Constructs new metadata
     *
     * @param string $class
     */
    public function __construct($class)
    {
        $this->setClassName($class);
    }

    /**
     * {@inheritdoc}
     */
    public function setClassName($class)
    {
        $this->class = (string) $class;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    public function getClassReflection()
    {
        return new \ReflectionClass($this->getClassName());
    }
}
