<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\Mapping\Driver;

use FSi\DoctrineExtensions\Mapping\Driver\AbstractXmlDriver;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use FSi\Component\Metadata\ClassMetadataInterface;

class Xml extends AbstractXmlDriver
{
    /**
     * {@inheritdoc}
     */
    protected function loadExtendedClassMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata)
    {
        $mapping = $this->getFileMapping($extendedClassMetadata);

        if (isset($mapping->field)) {
            foreach ($mapping->field as $fieldMapping) {
                $fieldMappingDoctrine = $fieldMapping;
                $fieldMapping = $fieldMapping->children(self::FSI_NAMESPACE_URI);
                if (isset($fieldMapping->uploadable)) {
                    $data = $fieldMapping->uploadable;
                    $extendedClassMetadata->addUploadableProperty(
                        $this->getAttribute($fieldMappingDoctrine, 'name'),
                        $this->getAttribute($data, 'targetField'),
                        $this->getAttribute($data, 'filesystem'),
                        $this->getAttribute($data, 'keymaker'),
                        $this->getAttribute($data, 'keyLength'),
                        $this->getAttribute($data, 'keyPattern')
                    );
                }
            }
        }
    }
}
