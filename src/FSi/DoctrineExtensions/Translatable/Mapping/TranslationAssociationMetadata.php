<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable\Mapping;

class TranslationAssociationMetadata
{
    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var string
     */
    private $association;

    /**
     * @var array
     */
    private $properties;

    public function __construct(ClassMetadata $classMetadata, $association, $properties)
    {
        $this->classMetadata = $classMetadata;
        $this->association = $association;
        $this->properties = $properties;
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }

    /**
     * @return string
     */
    public function getAssociationName()
    {
        return $this->association;
    }

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
