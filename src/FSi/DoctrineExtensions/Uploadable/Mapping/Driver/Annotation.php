<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use FSi\Component\Reflection\ReflectionClass;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Mapping\Driver\AbstractAnnotationDriver;

class Annotation extends AbstractAnnotationDriver
{
    const UPLOADABLE = 'FSi\\DoctrineExtensions\\Uploadable\\Mapping\\Annotation\\Uploadable';

    /**
     * {@inheritdoc}
     */
    protected function loadExtendedClassMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata)
    {
        $classReflection = $extendedClassMetadata->getClassReflection();
        foreach ($classReflection->getProperties() as $property) {
            if ($baseClassMetadata->isMappedSuperclass && !$property->isPrivate() ||
                $baseClassMetadata->isInheritedField($property->name) ||
                isset($baseClassMetadata->associationMappings[$property->name]['inherited'])
            ) {
                continue;
            }

            if ($uploadableAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($property, self::UPLOADABLE)) {
                $extendedClassMetadata->addUploadableProperty(
                    $property->getName(),
                    $uploadableAnnotation->targetField,
                    $uploadableAnnotation->filesystem,
                    $uploadableAnnotation->keymaker,
                    $uploadableAnnotation->keyLength,
                    $uploadableAnnotation->keyPattern
                );
            }
        }
    }
}
