<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable\Mapping;

use FSi\Component\Metadata\AbstractClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadata as DoctrineClassMetadata;

class ClassMetadata extends AbstractClassMetadata
{
    /**
     * @var string
     */
    public $localeProperty;

    /**
     * @var array
     */
    protected $translatableProperties = array();

    /**
     * Add specified property as translatable. The real translation is stored in $targetField inside
     * $translationAssociation.
     *
     * @param string $translationAssociation
     * @param string $property
     * @param string $targetProperty
     */
    public function addTranslatableProperty($translationAssociation, $property, $targetField = null)
    {
        if (!isset($targetField)) {
            $targetField = $property;
        }
        if (!isset($this->translatableProperties[$translationAssociation])) {
            $this->translatableProperties[$translationAssociation] = array();
        }
        $this->translatableProperties[$translationAssociation][$property] = $targetField;
    }

    /**
     * Returns true if associated class has any translatable properties.
     *
     * @return boolean
     */
    public function hasTranslatableProperties()
    {
        return !empty($this->translatableProperties);
    }

    /**
     * Returns array of all translatable properties indexed by translation association name and then by property name
     *
     * @return array
     */
    public function getTranslatableProperties()
    {
        return $this->translatableProperties;
    }

    /**
     * @return TranslationAssociationMetadata[]
     */
    public function getTranslationAssociationMetadatas()
    {
        $metadatas = array();

        foreach ($this->getTranslatableProperties() as $association => $properties) {
            $metadatas[] = new TranslationAssociationMetadata($this, $association, $properties);
        }

        return $metadatas;
    }
}
