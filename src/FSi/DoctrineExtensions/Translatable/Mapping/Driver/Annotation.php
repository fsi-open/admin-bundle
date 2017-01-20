<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use FSi\Component\Reflection\ReflectionClass;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Translatable\Exception\AnnotationException;
use FSi\DoctrineExtensions\Mapping\Driver\AbstractAnnotationDriver;

class Annotation extends AbstractAnnotationDriver
{
    const TRANSLATABLE = 'FSi\\DoctrineExtensions\\Translatable\\Mapping\\Annotation\\Translatable';
    const LOCALE       = 'FSi\\DoctrineExtensions\\Translatable\\Mapping\\Annotation\\Locale';

    /**
    * {@inheritDoc}
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

            if ($translatableAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($property, self::TRANSLATABLE)) {
                if (!isset($translatableAnnotation->mappedBy)) {
                    throw new AnnotationException(
                        'Annotation \'Translatable\' in property \''.$property.'\' of class \''.$baseClassMetadata->name.'\' does not have required \'mappedBy\' attribute'
                    );
                }

                $extendedClassMetadata->addTranslatableProperty(
                    $translatableAnnotation->mappedBy,
                    $property->getName(),
                    $translatableAnnotation->targetField
                );
            }

            if ($languageAnnotation = $this->getAnnotationReader()->getPropertyAnnotation($property, self::LOCALE)) {
                $extendedClassMetadata->localeProperty = $property->getName();
            }
        }
    }
}
