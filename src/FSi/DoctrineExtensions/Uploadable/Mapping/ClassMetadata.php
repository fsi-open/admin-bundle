<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\Mapping;

use FSi\Component\Metadata\AbstractClassMetadata;

class ClassMetadata extends AbstractClassMetadata
{
    /**
     * @var array
     */
    protected $uploadableProperties = array();

    /**
     * Set specified property as uploadable.
     *
     * @param string $property
     * @param string $targetField
     * @param string $filesystem
     * @param object $keymaker
     * @param integer $keyLength
     * @param null $keyPattern
     */
    public function addUploadableProperty($property, $targetField, $filesystem = null, $keymaker = null, $keyLength = null, $keyPattern = null)
    {
        $this->uploadableProperties[$property] = array(
            'targetField' => $targetField,
            'filesystem' => $filesystem,
            'keymaker' => $keymaker,
            'keyLength' => $keyLength,
            'keyPattern' => $keyPattern,
        );
    }

    /**
     * Returns true if associated class has any uploadable properties.
     *
     * @return boolean
     */
    public function hasUploadableProperties()
    {
        return !empty($this->uploadableProperties);
    }

    /**
     * Returns array of all uploadable properties indexed by property.
     *
     * @return array
     */
    public function getUploadableProperties()
    {
        return $this->uploadableProperties;
    }
}
