<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use FSi\DoctrineExtensions\Translatable\Mapping\TranslationAssociationMetadata;
use FSi\DoctrineExtensions\Translatable\Model\TranslatableRepositoryInterface;

/**
 * @internal
 */
class ClassTranslationContext
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var ClassMetadata
     */
    private $classMetadata;

    /**
     * @var TranslationAssociationMetadata
     */
    private $associationMetadata;

    public function __construct(
        ObjectManager $objectManager,
        ClassMetadata $classMetadata,
        TranslationAssociationMetadata $associationMetadata
    ) {
        $this->objectManager = $objectManager;
        $this->classMetadata = $classMetadata;
        $this->associationMetadata = $associationMetadata;
    }

    /**
     * @return ClassMetadata
     */
    public function getClassMetadata()
    {
        return $this->classMetadata;
    }

    /**
     * @return Mapping\ClassMetadata
     */
    public function getTranslatableMetadata()
    {
        return $this->associationMetadata->getClassMetadata();
    }

    /**
     * @return TranslationAssociationMetadata
     */
    public function getAssociationMetadata()
    {
        return $this->associationMetadata;
    }

    /**
     * @return ClassMetadata
     */
    public function getTranslationMetadata()
    {
        $associationName = $this->associationMetadata->getAssociationName();
        $translationClass = $this->classMetadata->getAssociationTargetClass($associationName);
        return $this->objectManager->getClassMetadata($translationClass);
    }

    /**
     * @return TranslatableRepositoryInterface
     * @throws Exception\AnnotationException
     */
    public function getTranslatableRepository()
    {
        $repository = $this->objectManager->getRepository($this->classMetadata->getName());

        if (!($repository instanceof TranslatableRepositoryInterface)) {
            throw new Exception\AnnotationException(sprintf(
                'Entity "%s" has "%s" as its "repositoryClass" which does not implement \FSi\DoctrineExtensions\Translatable\Model\TranslatableRepositoryInterface',
                $this->classMetadata->getName(),
                get_class($repository)
            ));
        }

        return $repository;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }
}
