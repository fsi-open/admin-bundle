<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata;

use Doctrine\Common\Cache\Cache;
use FSi\Component\Metadata\Driver\DriverInterface;

class MetadataFactory
{
    const METADATA_CLASS = 'FSi\Component\Metadata\ClassMetadata';

    /**
     * Driver used to read metadata.
     *
     * @var \FSi\Component\Metadata\Driver\DriverInterface
     */
    protected $driver;

    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    protected $cache;

    /**
     * Prefix used in for each value stored in cache.
     *
     * @var string
     */
    protected $cachePrefix;

    /**
     * Name of class used to store metada
     *
     * @var string
     */
    protected $metadataClassName;

    /**
     * Array of already loaded class metadata
     *
     * @var array
     */
    protected $loadedMetadata = array();

    /**
     * Create metadataFactory. Sometimes it might be usefull to create own ClassMetadata.
     *
     * @throws InvalidArgumentException
     * @param DriverInterface $driver
     * @param Cache $cache
     * @param string $cachePrefix
     * @param string $metadataClassName
     */
    public function __construct(DriverInterface $driver, Cache $cache = null,
        $cachePrefix = null, $metadataClassName = null)
    {
        $this->driver = $driver;
        if (isset($cache)) {
            $this->cache  = $cache;
            if (isset($cachePrefix)) {
                $this->cachePrefix = $cachePrefix;
            }
        }
        if (isset($metadataClassName)) {
            $metadataClassName = ltrim($metadataClassName, '\\');
            $metadataClassReflection = new \ReflectionClass($metadataClassName);
            if (!$metadataClassReflection->implementsInterface('FSi\Component\Metadata\ClassMetadataInterface')) {
                throw new \InvalidArgumentException('Metadata class must implement FSi\Component\Metadata\ClassMetadataInterface');
            }
            $this->metadataClassName = $metadataClassName;
        } else {
            $this->metadataClassName = self::METADATA_CLASS;
        }
    }

    /**
     * Returns class metadata read by the driver. This method calls itself recursively for each ancestor class
     *
     * @param string $class
     * @return \FSi\Component\Metadata\ClassMetadataInterface
     */
    public function getClassMetadata($class)
    {
        $class = ltrim($class, '\\');
        $metadataIndex = $this->getCacheId($class);

        if (isset($this->loadedMetadata[$metadataIndex])) {
            return $this->loadedMetadata[$metadataIndex];
        }

        if (isset($this->cache)) {
            if (false !== ($metadata = $this->cache->fetch($metadataIndex))) {
                return $metadata;
            }
        }

        $metadata = new $this->metadataClassName($class);

        $parentClasses = array_reverse(class_parents($class));
        foreach ($parentClasses as $parentClass) {
            $metadata->setClassName($parentClass);
            $this->driver->loadClassMetadata($metadata);
        }

        $metadata->setClassName($class);
        $this->driver->loadClassMetadata($metadata);

        if (isset($this->cache)) {
            $this->cache->save($metadataIndex, $metadata);
        }
        $this->loadedMetadata[$metadataIndex] = $metadata;

        return $metadata;
    }

    /**
     * Returns identifier used to store class metadata in cache
     *
     * @param string $class
     */
    protected function getCacheId($class)
    {
        return $this->cachePrefix . $this->metadataClassName . $class;
    }
}
