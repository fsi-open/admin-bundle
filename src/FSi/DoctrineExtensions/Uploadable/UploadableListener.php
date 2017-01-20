<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Uploadable;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use FSi\Component\Metadata\ClassMetadataInterface;
use FSi\Component\PropertyObserver\PropertyObserver;
use FSi\DoctrineExtensions\Mapping\Event\AdapterInterface;
use FSi\DoctrineExtensions\Mapping\MappedEventSubscriber;
use FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException;
use FSi\DoctrineExtensions\Uploadable\Exception\MappingException;
use FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface;
use FSi\DoctrineExtensions\Uploadable\Keymaker\KeymakerInterface;
use Gaufrette\Filesystem;
use Gaufrette\FilesystemMap;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\Proxy\Proxy;

class UploadableListener extends MappedEventSubscriber
{
    /**
     * Default key length.
     */
    const KEY_LENGTH = 255;

    /**
     * @var array
     */
    protected $filesystems = array();

    /**
     * @var Filesystem
     *
     * Default filesystem id.
     */
    protected $defaultFilesystem;

    /**
     * @var integer
     */
    protected $defaultKeyLength = self::KEY_LENGTH;

    /**
     * @var Keymaker/KeymakerInterface
     */
    protected $defaultKeymaker;

    /**
     * @var array
     */
    protected $toDelete = array();

    /**
     * @var \FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface
     */
    protected $fileHandler;

    /**
     * @param array|\Gaufrette\FilesystemMap $filesystems
     * @param \FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface $fileHandler
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function __construct($filesystems, FileHandler\FileHandlerInterface $fileHandler)
    {
        // Filesystems.
        $this->setFilesystems($filesystems);

        // Set file handler.
        $this->setFileHandler($fileHandler);
    }

    /**
     * @param array|\Gaufrette\FilesystemMap $filesystems
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function setFilesystems($filesystems)
    {
        $this->filesystems = array();

        if ($filesystems instanceof FilesystemMap) {
            $filesystems = $filesystems->all();
        }

        if (is_array($filesystems)) {
            foreach ($filesystems as $id => $filesystem) {
                $this->setFilesystem($id, $filesystem);
            }
        } else {
            throw new RuntimeException(sprintf(
                'Option "filesystems" must be type of "array" or "Gaufrette\FilesystemMap", "%s" given.',
                is_object($filesystems) ? get_class($filesystems) : gettype($filesystems)
            ));
        }
    }

    /**
     * @param string $id
     * @param \Gaufrette\Filesystem $filesystem
     */
    public function setFilesystem($id, Filesystem $filesystem)
    {
        $this->filesystems[$id] = $filesystem;
    }

    /**
     * @param string $id
     */
    public function removeFilesystem($id)
    {
        unset($this->filesystems[$id]);
    }

    /**
     * @return \Gaufrette\Filesystem[]
     */
    public function getFilesystems()
    {
        return $this->filesystems;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return array(
            'preFlush',
            'postLoad',
            'postPersist',
            'postFlush',
            'postRemove',
        );
    }

    /**
     * @param \Gaufrette\Filesystem $filesystem
     */
    public function setDefaultFilesystem(Filesystem $filesystem)
    {
        $this->defaultFilesystem = $filesystem;
    }

    /**
     * @return bool
     */
    public function hasDefaultFilesystem()
    {
        return isset($this->defaultFilesystem);
    }

    /**
     * @return \Gaufrette\Filesystem
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function getDefaultFilesystem()
    {
        if (!$this->hasDefaultFilesystem()) {
            throw new RuntimeException('There\'s no default filesystem set.');
        }
        return $this->defaultFilesystem;
    }

    /**
     * @param string $id
     * @return bool
     */
    public function hasFilesystem($id)
    {
        return isset($this->filesystems[$id]);
    }

    /**
     * @param string $id
     * @return \Gaufrette\Filesystem
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function getFilesystem($id)
    {
        if (!$this->hasFilesystem($id)) {
            throw new RuntimeException(sprintf('There is no filesystem for id "%s".', $id));
        }
        return $this->filesystems[$id];
    }

    /**
     * After loading the entity load file if any.
     *
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postLoad(LifecycleEventArgs  $eventArgs)
    {
        $eventAdapter = $this->getEventAdapter($eventArgs);
        $objectManager = $eventAdapter->getObjectManager();
        $object = $eventAdapter->getObject();
        $uploadableMeta = $this->getObjectExtendedMetadata($objectManager, $object);

        if ($uploadableMeta->hasUploadableProperties()) {
            $this->loadFiles($object, $uploadableMeta, $objectManager);
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $eventAdapter = $this->getEventAdapter($eventArgs);
        $objectManager = $eventAdapter->getObjectManager();
        $object = $eventAdapter->getObject();
        $meta = $objectManager->getClassMetadata(get_class($object));
        $uploadableMeta = $this->getExtendedMetadata($objectManager, $meta->name);

        if ($uploadableMeta->hasUploadableProperties()) {
            $this->updateFiles($objectManager, $uploadableMeta, $object, $eventAdapter);
            $uow = $objectManager->getUnitOfWork();
            $uow->computeChangeSet($meta, $object);
        }
    }

    /**
     * Check and eventually update files keys.
     *
     * @param \Doctrine\ORM\Event\PreFlushEventArgs $eventArgs
     */
    public function preFlush(PreFlushEventArgs $eventArgs)
    {
        $entityManager = $eventArgs->getEntityManager();
        $unitOfWork = $entityManager->getUnitOfWork();
        $eventAdapter = $this->getEventAdapter($eventArgs);

        foreach ($unitOfWork->getIdentityMap() as $class => $entities) {
            foreach ($entities as $object) {
                $uploadableMeta = $this->getObjectExtendedMetadata($entityManager, $object);
                if (!$uploadableMeta->hasUploadableProperties()) {
                    continue;
                }
                $this->updateFiles($entityManager, $uploadableMeta, $object, $eventAdapter);
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Event\PostFlushEventArgs $eventArgs
     */
    public function postFlush(PostFlushEventArgs $eventArgs)
    {
        foreach ($this->toDelete as $file) {
            if ($file->exists()) {
                $file->delete();
            }
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $eventAdapter = $this->getEventAdapter($eventArgs);
        $objectManager = $eventAdapter->getObjectManager();
        $object = $eventAdapter->getObject();
        $uploadableMeta = $this->getObjectExtendedMetadata($objectManager, $object);

        if ($uploadableMeta->hasUploadableProperties()) {
            $this->deleteFiles($uploadableMeta, $object);
        }
    }

    /**
     * @param int $length
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function setDefaultKeyLength($length)
    {
        if ($length < 1) {
            throw new RuntimeException(sprintf('Key length must be greater than "%d"', $length));
        }

        $this->defaultKeyLength = $length;
    }

    /**
     * @return int
     */
    public function getDefaultKeyLength()
    {
        return $this->defaultKeyLength;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\Keymaker\KeymakerInterface $keymaker
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    public function setDefaultKeymaker(KeymakerInterface $keymaker)
    {
        $this->defaultKeymaker = $keymaker;
    }

    /**
     * @return bool
     */
    public function hasDefaultKeymaker()
    {
        return isset($this->defaultKeymaker);
    }

    /**
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     * @return \FSi\DoctrineExtensions\Uploadable\Keymaker\KeymakerInterface
     */
    public function getDefaultKeymaker()
    {
        if (!$this->hasDefaultKeymaker()) {
            throw new RuntimeException('There is no default keymaker set.');
        }

        return $this->defaultKeymaker;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\Filehandler\FileHandlerInterface $fileHandler
     */
    public function setFileHandler(Filehandler\FileHandlerInterface $fileHandler)
    {
        $this->fileHandler = $fileHandler;
    }

    /**
     * @return \FSi\DoctrineExtensions\Uploadable\FileHandler\FileHandlerInterface
     */
    public function getFileHandler()
    {
        return $this->fileHandler;
    }

    /**
     * Load object files and attach observers for key fields.
     *
     * @param object $object
     * @param \FSi\DoctrineExtensions\Uploadable\Mapping\ClassMetadata $uploadableMeta
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     */
    protected function loadFiles($object, $uploadableMeta, $objectManager)
    {
        $propertyObserver = $this->getPropertyObserver($objectManager);
        foreach ($uploadableMeta->getUploadableProperties() as $property => $config) {
            // File key.
            $key = PropertyAccess::createPropertyAccessor()->getValue($object, $property);

            // Injecting file.
            if (!empty($key)) {
                $filesystem = $this->computeFilesystem($config);
                $file = new File($key, $filesystem);
                $propertyObserver->setValue($object, $config['targetField'], $file);
            }
        }
    }

    /**
     * Updating files keys.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param \FSi\DoctrineExtensions\Uploadable\Mapping\ClassMetadata $uploadableMeta
     * @param object $object
     * @param \FSi\DoctrineExtensions\Mapping\Event\AdapterInterface $eventAdapter
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    protected function updateFiles(ObjectManager $objectManager, $uploadableMeta, $object, AdapterInterface $eventAdapter)
    {
        $propertyObserver = $this->getPropertyObserver($objectManager);

        if ($object instanceof \Doctrine\Common\Persistence\Proxy) {
            $object->__load();
        }

        $id = $eventAdapter->extractIdentifier($objectManager, $object, false);
        $id = implode('-', $id);

        foreach ($uploadableMeta->getUploadableProperties() as $property => $config) {
            if (!$propertyObserver->hasSavedValue($object, $config['targetField']) || $propertyObserver->hasValueChanged($object, $config['targetField'])) {
                $accessor = PropertyAccess::createPropertyAccessor();
                $file = $accessor->getValue($object, $config['targetField']);

                $filesystem = $this->computeFilesystem($config);

                // Since file has changed, the old one should be removed.
                if ($oldKey = $accessor->getValue($object, $property)) {
                    if ($oldFile = $propertyObserver->getSavedValue($object, $config['targetField'])) {
                        $this->addToDelete($oldFile);
                    }
                }

                if (empty($file)) {
                    $accessor->setValue($object, $property, null);
                    $propertyObserver->saveValue($object, $config['targetField']);
                    continue;
                }

                if (!$this->getFileHandler()->supports($file)) {
                    throw new RuntimeException(sprintf('Can\'t handle resource of type "%s".', is_object($file) ? get_class($file) : gettype($file)));
                }

                $keymaker = $this->computeKeymaker($config);
                $keyLength = $this->computeKeyLength($config);
                $keyPattern = $config['keyPattern'] ? $config['keyPattern'] : null;

                $fileName = $this->getFileHandler()->getName($file);

                $newKey = $this->generateNewKey($keymaker, $object, $property, $id, $fileName, $keyLength, $keyPattern, $filesystem);

                $newFile = new File($newKey, $filesystem);
                $newFile->setContent($this->getFileHandler()->getContent($file));
                $accessor->setValue($object, $property, $newFile->getKey());
                // Save its current value, so if another update will be called, there won't be another saving.
                $propertyObserver->setValue($object, $config['targetField'], $newFile);
            }
        }
    }

    /**
     * Deleting files.
     *
     * @param \FSi\DoctrineExtensions\Uploadable\Mapping\ClassMetadata $uploadableMeta
     * @param object $object
     * @throws \FSi\DoctrineExtensions\Uploadable\Exception\RuntimeException
     */
    protected function deleteFiles($uploadableMeta, $object)
    {
        foreach ($uploadableMeta->getUploadableProperties() as $property => $config) {
            if ($oldKey = PropertyAccess::createPropertyAccessor()->getValue($object, $property)) {
                $this->addToDelete(new File($oldKey, $this->computeFilesystem($config)));
            }
        }
    }

    /**
     * Returns PropertyObserver for specified ObjectManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @return mixed
     */
    protected function getPropertyObserver(ObjectManager $objectManager)
    {
        $oid = spl_object_hash($objectManager);
        if (!isset($this->propertyObservers[$oid])) {
            $this->propertyObservers[$oid] = new PropertyObserver();
        }
        return $this->propertyObservers[$oid];
    }

    /**
     * {@inheritdoc}
     */
    protected function validateExtendedMetadata(ClassMetadata $baseClassMetadata, ClassMetadataInterface $extendedClassMetadata)
    {
        foreach ($extendedClassMetadata->getUploadableProperties() as $field => $options) {
            if (empty($options['targetField'])) {
                throw new MappingException(sprintf('Mapping "Uploadable" in property "%s" of class "%s" does not have required "targetField" attribute, or attribute is empty.', $field, $baseClassMetadata->name));
            }

            if (!property_exists($baseClassMetadata->name, $options['targetField'])) {
                throw new MappingException(sprintf('Mapping "Uploadable" in property "%s" of class "%s" has "targetField" set to "%s", which doesn\'t exist.', $field, $baseClassMetadata->name, $options['targetField']));
            }

            if ($baseClassMetadata->hasField($options['targetField'])) {
                throw new MappingException(sprintf('Mapping "Uploadable" in property "%s" of class "%s" have "targetField" that points at already mapped field ("%s").', $field, $baseClassMetadata->name, $options['targetField']));
            }

            if (!$baseClassMetadata->hasField($field)) {
                throw new MappingException(sprintf('Property "%s" of class "%s" have mapping "Uploadable" but isn\'t mapped as Doctrine\'s column.', $field, $baseClassMetadata->name, $options['targetField']));
            }

            if (!is_null($options['keyLength']) and !is_numeric($options['keyLength'])) {
                throw new MappingException(sprintf('Property "%s" of class "%s" have mapping "Uploadable" with key length is not a number.', $field, $baseClassMetadata->name, $options['targetField']));
            }

            if (!is_null($options['keyLength']) and $options['keyLength'] < 1) {
                throw new MappingException(sprintf('Property "%s" of class "%s" have mapping "Uploadable" with key length less than 1.', $field, $baseClassMetadata->name, $options['targetField']));
            }

            if (!is_null($options['keymaker']) and !$options['keymaker'] instanceof KeymakerInterface) {
                throw new MappingException(sprintf(
                    'Mapping "Uploadable" in property "%s" of class "%s" does have keymaker that isn\'t instance of expected FSi\\DoctrineExtensions\\Uploadable\\Keymaker\\KeymakerInterface ("%s" given).',
                    $field,
                    $baseClassMetadata->name,
                    is_object($options['keymaker']) ? get_class($options['keymaker']) : gettype($options['keymaker'])
                ));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }

    /**
     * @param \FSi\DoctrineExtensions\Uploadable\File $file
     */
    protected function addToDelete(File $file)
    {
        $this->toDelete[] = $file;
    }

    /**
     * @param array $config
     * @return \Gaufrette\Filesystem
     */
    private function computeFilesystem(array $config)
    {
        return !empty($config['filesystem']) ? $this->getFilesystem($config['filesystem']) : $this->getDefaultFilesystem();
    }

    /**
     * @param array $config
     * @return \FSi\DoctrineExtensions\Uploadable\Keymaker\KeymakerInterface
     */
    private function computeKeymaker($config)
    {
        return !empty($config['keymaker']) ? $config['keymaker'] : $this->getDefaultKeymaker();
    }

    /**
     * @param array $config
     * @return integer
     */
    private function computeKeyLength($config)
    {
        return !empty($config['keyLength']) ? $config['keyLength'] : $this->getDefaultKeyLength();
    }

    /**
     * Algorithm to transform names from name.txt to name_i.txt and name_i.txt into name_{i++}.txt
     * when given key already exists and can't be reused.
     *
     * @param \FSi\DoctrineExtensions\Uploadable\Keymaker\KeymakerInterface $keymaker
     * @param object $object
     * @param string $property
     * @param mixed $id
     * @param string $fileName
     * @param integer $keyLength
     * @param string $keyPattern
     * @param \Gaufrette\Filesystem $filesystem
     * @return string
     */
    private function generateNewKey(KeymakerInterface $keymaker, $object, $property, $id, $fileName, $keyLength, $keyPattern, Filesystem $filesystem)
    {
        while ($filesystem->has($newKey = $keymaker->createKey($object, $property, $id, $fileName, $keyPattern))) {
            if ($match = preg_match('/(.*)_(\d+)(\.[^\.]*)?$/', $fileName, $matches)) {
                $fileName = sprintf('%s_%s%s', $matches[1], strval($matches[2] + 1), isset($matches[3])?$matches[3]:'');
            } else {
                $fileParts = explode('.', $fileName);
                if (count($fileParts) > 1) {
                    $fileParts[count($fileParts)  - 2] .= '_1';
                    $fileName = implode('.', $fileParts);
                } else {
                    $fileName .= '_1';
                }
            }
        }

        if (mb_strlen($newKey) > $keyLength) {
            throw new RuntimeException(sprintf('Generated key exceeded limit of %d characters (had %d characters).', $keyLength, mb_strlen($newKey)));
        }

        return $newKey;
    }
}
