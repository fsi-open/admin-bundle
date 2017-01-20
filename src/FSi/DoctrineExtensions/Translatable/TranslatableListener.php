<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Translatable;

use Doctrine\Common\EventArgs;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\PreFlushEventArgs;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Mapping\MappedEventSubscriber;
use FSi\DoctrineExtensions\Translatable\Entity\Repository\TranslatableRepository;
use FSi\DoctrineExtensions\Translatable\Exception;
use FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata as TranslatableClassMetadata;
use FSi\DoctrineExtensions\Translatable\Mapping\TranslationAssociationMetadata;
use FSi\DoctrineExtensions\Translatable\Model\TranslatableRepositoryInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class TranslatableListener extends MappedEventSubscriber
{
    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private $_propertyAccessor;

    /**
     * Current locale of the listener
     *
     * @var mixed
     */
    private $_currentLocale;

    /**
     * Default locale of the listener used when there is no translation in current locale
     *
     * @var mixed
     */
    private $_defaultLocale;

    /**
     * @var TranslationHelper
     */
    private $translationHelper;

    /**
     * @var array
     */
    private $classTranslationContexts;

    /**
     */
    public function __construct()
    {
        $this->translationHelper = new TranslationHelper($this->getPropertyAccessor());
    }

    /**
     * Set the current locale
     *
     * @param mixed $locale
     * @return \FSi\DoctrineExtensions\Translatable\TranslatableListener
     */
    public function setLocale($locale)
    {
        $this->_currentLocale = $locale;
        return $this;
    }

    /**
     * Get the current locale
     *
     * @return mixed
     */
    public function getLocale()
    {
        return $this->_currentLocale;
    }

    /**
     * Set the default locale
     *
     * @param mixed $defaultLocale
     * @return \FSi\DoctrineExtensions\Translatable\TranslatableListener
     */
    public function setDefaultLocale($defaultLocale)
    {
        $this->_defaultLocale = $defaultLocale;
        return $this;
    }

    /**
     * Get the default locale
     *
     * @return mixed
     */
    public function getDefaultLocale()
    {
        return $this->_defaultLocale;
    }

    /**
     * Specifies the list of events to listen
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            'postLoad',
            'postHydrate',
            'preFlush'
        );
    }

    /**
     * Get the namespace of extension event subscriber
     * used for cache id of extensions also to know where
     * to find Mapping drivers and event adapters
     *
     * @return string
     */
    public function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * After loading the entity copy the current translation fields into non-persistent translatable properties
     *
     * @param \Doctrine\Common\EventArgs $eventArgs
     */
    public function postLoad(EventArgs $eventArgs)
    {
        $this->loadTranslation(
            $this->getEventObjectManager($eventArgs),
            $this->getEventObject($eventArgs),
            $this->getLocale()
        );
    }

    /**
     * After loading the entity copy the current translation fields into non-persistent translatable properties
     *
     * @param \Doctrine\Common\EventArgs $eventArgs
     */
    public function postHydrate(EventArgs $eventArgs)
    {
        $this->postLoad($eventArgs);
    }

    /**
     * This event handler will update, insert or remove translation entities if main object's translatable properties change.
     *
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $eventArgs
     * @return void
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork    = $entityManager->getUnitOfWork();

        foreach ($unitOfWork->getScheduledEntityInsertions() as $object) {
            $this->updateObjectTranslations($entityManager, $object);
        }

        foreach ($unitOfWork->getIdentityMap() as $class => $entities) {
            foreach ($entities as $object) {
                $this->updateObjectTranslations($entityManager, $object);
            }
        }
    }

    /**
     * Load translations fields into object properties
     *
     * @param ObjectManager $objectManager
     * @param object $object
     * @param string $locale
     */
    public function loadTranslation(ObjectManager $objectManager, $object, $locale)
    {
        $translatableMeta = $this->getTranslatableMetadata($objectManager, $object);
        if (!$translatableMeta->hasTranslatableProperties()) {
            return;
        }

        foreach ($translatableMeta->getTranslationAssociationMetadatas() as $associationMeta) {

            $context = $this->getTranslationContext($objectManager, $associationMeta, $object);
            $associationName = $associationMeta->getAssociationName();
            $repository = $context->getTranslatableRepository();
            $translation = $repository->findTranslation($object, $locale, $associationName);

            //default locale fallback
            if (!isset($translation) && isset($this->_defaultLocale) && $this->_defaultLocale !== $locale) {
                $locale = $this->_defaultLocale;
                $translation = $repository->findTranslation($object, $this->_defaultLocale, $associationName);
            }

            if (isset($translation)) {
                $this->translationHelper->copyTranslationProperties($context, $object, $translation, $locale);
            } else {
                $this->translationHelper->clearTranslatableProperties($context, $object);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function validateExtendedMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata)
    {
        if ($extendedClassMetadata->hasTranslatableProperties()) {
            $this->validateTranslatableLocaleProperty($baseClassMetadata, $extendedClassMetadata);
            $this->validateTranslatableProperties($baseClassMetadata, $extendedClassMetadata);
        } elseif (isset($extendedClassMetadata->localeProperty)) {
            $this->validateTranslationLocaleProperty($baseClassMetadata, $extendedClassMetadata);
        }
    }

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    private function getPropertyAccessor()
    {
        if (!isset($this->_propertyAccessor)) {
            $this->_propertyAccessor = PropertyAccess::createPropertyAccessor();
        }

        return $this->_propertyAccessor;
    }

    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $baseClassMetadata
     * @param \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata $translatableClassMetadata
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\MappingException
     */
    private function validateTranslatableLocaleProperty(ClassMetadata $baseClassMetadata, TranslatableClassMetadata $translatableClassMetadata)
    {
        if (!isset($translatableClassMetadata->localeProperty)) {
            throw new Exception\MappingException(sprintf(
                "Entity '%s' has translatable properties so it must have property marked with @Translatable\\Language annotation",
                $baseClassMetadata->getName()
            ));
        }
        if ($baseClassMetadata->hasField($translatableClassMetadata->localeProperty) ||
            $baseClassMetadata->hasAssociation($translatableClassMetadata->localeProperty)) {
            throw new Exception\MappingException(sprintf(
                "Entity '%s' seems to be a translatable entity so its '%s' field must not be persistent",
                $baseClassMetadata->getName(),
                $translatableClassMetadata->localeProperty
            ));
        }
    }

    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $baseClassMetadata
     * @param \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata $translatableClassMetadata
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\MappingException
     */
    private function validateTranslatableProperties(
        ClassMetadata $baseClassMetadata,
        TranslatableClassMetadata $translatableClassMetadata
    ) {
        $translatableProperties = $translatableClassMetadata->getTranslatableProperties();
        foreach ($translatableProperties as $translation => $properties) {
            if (!$baseClassMetadata->hasAssociation($translation) ||
                !$baseClassMetadata->isCollectionValuedAssociation($translation)) {
                throw new Exception\MappingException(sprintf(
                    "Field '%s' in entity '%s' has to be a OneToMany association",
                    $translation,
                    $baseClassMetadata->getName()
                ));
            }
        }
    }

    /**
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $baseClassMetadata
     * @param \FSi\DoctrineExtensions\Translatable\Mapping\ClassMetadata $translatableClassMetadata
     * @throws \FSi\DoctrineExtensions\Translatable\Exception\MappingException
     */
    private function validateTranslationLocaleProperty(ClassMetadata $baseClassMetadata, TranslatableClassMetadata $translatableClassMetadata)
    {
        if (!$baseClassMetadata->hasField($translatableClassMetadata->localeProperty) &&
            !$baseClassMetadata->hasAssociation($translatableClassMetadata->localeProperty)) {
            throw new Exception\MappingException(sprintf(
                "Entity '%s' seems to be a translation entity so its '%s' field must be persistent",
                $baseClassMetadata->getName(),
                $translatableClassMetadata->localeProperty
            ));
        }
    }

    /**
     * Helper method to insert, remove or update translations entities associated with specified object
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param object $object
     */
    private function updateObjectTranslations(ObjectManager $objectManager, $object)
    {
        $translatableMeta = $this->getTranslatableMetadata($objectManager, $object);
        if (!$translatableMeta->hasTranslatableProperties()) {
            return;
        }

        foreach ($translatableMeta->getTranslationAssociationMetadatas() as $associationMeta) {

            $context = $this->getTranslationContext($objectManager, $associationMeta, $object);

            $locale = $this->translationHelper->getObjectLocale($context, $object);
            if (!isset($locale)) {
                $locale = $this->getLocale();
            }

            $hasTranslatedProperties = $this->translationHelper->hasTranslatedProperties($context, $object);

            if (!isset($locale) && $hasTranslatedProperties) {
                throw new Exception\RuntimeException(
                    "Neither object's locale nor the current locale was set for translatable properties"
                );
            }

            if ($hasTranslatedProperties) {
                $this->translationHelper->copyPropertiesToTranslation(
                    $context,
                    $object,
                    $locale
                );
            } else {
                $this->translationHelper->removeEmptyTranslation(
                    $context,
                    $object
                );
            }
        }
    }

    /**
     * @param ObjectManager $objectManager
     * @param TranslationAssociationMetadata $associationMeta
     * @param $object
     * @return ClassTranslationContext
     */
    private function getTranslationContext(
        ObjectManager $objectManager,
        TranslationAssociationMetadata $associationMeta,
        $object
    ) {
        $classMeta = $this->getObjectClassMetadata($objectManager, $object);
        $className = $classMeta->getName();
        $associationName = $associationMeta->getAssociationName();

        if (empty($this->classTranslationContexts[$className][$associationName])) {
            $context = new ClassTranslationContext($objectManager, $classMeta, $associationMeta);
            $this->classTranslationContexts[$className][$associationName] = $context;
        }

        return $this->classTranslationContexts[$className][$associationName];
    }

    /**
     * @param ObjectManager $objectManager
     * @param object $object
     * @return ClassMetadata
     */
    private function getObjectClassMetadata(ObjectManager $objectManager, $object)
    {
        return $objectManager->getClassMetadata(get_class($object));
    }

    /**
     * @param ObjectManager $objectManager
     * @param object $object
     * @return TranslatableClassMetadata
     */
    private function getTranslatableMetadata(ObjectManager $objectManager, $object)
    {
        $meta = $this->getObjectClassMetadata($objectManager, $object);
        return $this->getExtendedMetadata($objectManager, $meta->getName());
    }
}
