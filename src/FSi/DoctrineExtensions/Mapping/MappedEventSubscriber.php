<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Annotations\Reader;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\EventArgs;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\DoctrineExtensions\Mapping\Exception;

/**
 * This is extension of event subscriber class and is
 * used specifically for handling the extension metadata
 * mapping for extensions.
 *
 * It dries up some reusable code which is common for
 * all extensions who mapps additional metadata through
 * extended drivers.
 *
 * Extension is based at Gedmo\Mapping.
 */
abstract class MappedEventSubscriber implements EventSubscriber
{
    /**
     * ExtensionMetadataFactory used to read the extension
     * metadata through the extension drivers.
     *
     * @var \FSi\DoctrineExtensions\Mapping\ExtensionMetadataFactory[]
     */
    private $extensionMetadataFactory = array();

    /**
     * List of event adapters used for this listener.
     *
     * @var array
     */
    private $adapters = array();

    /**
     * Custom annotation reader.
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $annotationReader;

    /**
     * Default annotation reader.
     *
     * @var \Doctrine\Common\Annotations\Reader
     */
    private $defaultAnnotationReader;

    /**
     * Get an event adapter to handle event specific methods.
     *
     * @param \Doctrine\Common\EventArgs $args
     * @throws \FSi\DoctrineExtensions\Mapping\Exception\RuntimeException - if event is not recognized
     * @return \FSi\DoctrineExtensions\Mapping\Event\AdapterInterface
     */
    protected function getEventAdapter(EventArgs $args)
    {
        $class = get_class($args);
        if (preg_match('@Doctrine\\\([^\\\]+)@', $class, $m) && in_array($m[1], array('ODM', 'ORM'))) {
            if (!isset($this->adapters[$m[1]])) {
                $adapterClass = $this->getNamespace() . '\\Mapping\\Event\\Adapter\\' . $m[1];
                if (!class_exists($adapterClass)) {
                    $adapterClass = 'FSi\\DoctrineExtensions\\Mapping\\Event\\Adapter\\'.$m[1];
                }
                $this->adapters[$m[1]] = new $adapterClass;
            }
            $this->adapters[$m[1]]->setEventArgs($args);
            return $this->adapters[$m[1]];
        } else {
            throw new Exception\RuntimeException('Event mapper does not support event arg class: '.$class);
        }
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEventObjectManager(EventArgs $args)
    {
        return $this->getEventAdapter($args)->getObjectManager();
    }

    /**
     * @param \Doctrine\Common\EventArgs $args
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEventObject(EventArgs $args)
    {
        return $this->getEventAdapter($args)->getObject();
    }

    /**
     * Get extended metadata mapping reader.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @return \FSi\Component\Metadata\MetadataFactory
     */
    protected function getExtendedMetadataFactory(ObjectManager $objectManager)
    {
        $oid = spl_object_hash($objectManager);
        if (!isset($this->extensionMetadataFactory[$oid])) {
            if (is_null($this->annotationReader)) {
                $this->annotationReader = $this->getDefaultAnnotationReader();
            }
            $this->extensionMetadataFactory[$oid] = new ExtendedMetadataFactory(
                $objectManager,
                $this->getNamespace(),
                $this->annotationReader
            );
        }
        return $this->extensionMetadataFactory[$oid];
    }

    /**
     * Sets the annotation reader which is passed further to the annotation driver.
     *
     * @param \Doctrine\Common\Annotations\Reader $reader
     */
    public function setAnnotationReader(Reader $reader)
    {
        $this->annotationReader = $reader;
    }

    /**
     * Scans the objects for extended annotations
     * event subscribers must subscribe to loadClassMetadata event.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $class
     * @return \FSi\Component\Metadata\ClassMetadataInterface
     */
    public function getExtendedMetadata(ObjectManager $objectManager, $class)
    {
        $factory = $this->getExtendedMetadataFactory($objectManager);
        $extendedMetadata = $factory->getClassMetadata($class);
        $metadata = $objectManager->getClassMetadata($class);
        if (!$metadata->isMappedSuperclass) {
            $this->validateExtendedMetadata($metadata, $extendedMetadata);
        }
        return $extendedMetadata;
    }

    /**
     * Validate complete metadata for final class (i.e. that is not mapped-superclass).
     *
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadata $baseClassMetadata
     * @param \FSi\Component\Metadata\ClassMetadataInterface $extendedClassMetadata
     */
    abstract protected function validateExtendedMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata);

    /**
     * Get the namespace of extension event subscriber
     * used for cache id of extensions also to know where
     * to find Mapping drivers and event adapters.
     *
     * @return string
     */
    abstract protected function getNamespace();

    /**
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param $object
     * @return \FSi\Component\Metadata\ClassMetadataInterface
     */
    protected function getObjectExtendedMetadata(ObjectManager $objectManager, $object)
    {
        $meta = $objectManager->getMetadataFactory()->getMetadataFor(get_class($object));
        return $this->getExtendedMetadata($objectManager, $meta->getName());
    }

    /**
     * Create default annotation reader for extensions.
     *
     * @return \Doctrine\Common\Annotations\AnnotationReader
     */
    private function getDefaultAnnotationReader()
    {
        if (null === $this->defaultAnnotationReader) {
            $reader = new \Doctrine\Common\Annotations\AnnotationReader();
            $reader = new \Doctrine\Common\Annotations\CachedReader($reader, new ArrayCache());
            $this->defaultAnnotationReader = $reader;
        }
        return $this->defaultAnnotationReader;
    }
}
