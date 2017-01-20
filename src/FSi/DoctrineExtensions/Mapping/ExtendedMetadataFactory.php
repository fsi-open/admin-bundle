<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Component\Metadata\Driver\AbstractAnnotationDriver;
use FSi\Component\Metadata\Driver\AbstractFileDriver;
use FSi\DoctrineExtensions\Mapping\Driver\DriverChain;
use FSi\Component\Metadata\MetadataFactory;
use FSi\DoctrineExtensions\Mapping\Driver\DriverInterface;
use FSi\DoctrineExtensions\Mapping\Exception;

final class ExtendedMetadataFactory extends MetadataFactory
{
    /**
     * Object manager, entity or document.
     *
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $objectManager;

    /**
     * Extension namespace.
     *
     * @var string
     */
    private $extensionNamespace;

    /**
     * Annotation reader.
     *
     * @var object
     */
    private $annotationReader;

    /**
     * Initializes extension driver.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $objectManager
     * @param string $extensionNamespace
     * @param object $annotationReader
     */
    public function __construct(ObjectManager $objectManager, $extensionNamespace, $annotationReader)
    {
        $this->objectManager = $objectManager;
        $this->annotationReader = $annotationReader;
        $this->extensionNamespace = $extensionNamespace;
        $omDriver = $objectManager->getConfiguration()->getMetadataDriverImpl();
        $omCache = $this->objectManager->getMetadataFactory()->getCacheDriver();
        $metadataClassName = null;
        if (class_exists($this->extensionNamespace . '\Mapping\ClassMetadata')) {
            $metadataClassName = $this->extensionNamespace . '\Mapping\ClassMetadata';
        }
        $driver = $this->getDriver($omDriver);
        $driver->setBaseMetadataFactory($objectManager->getMetadataFactory());
        parent::__construct($driver, $omCache, $extensionNamespace, $metadataClassName);
    }

    /**
     * Get the extended driver instance which will
     * read the metadata required by extension.
     *
     * @param object $omDriver
     * @throws \FSi\DoctrineExtensions\Mapping\Exception\RuntimeException if driver was not found in extension or it is not compatible
     * @return \FSi\DoctrineExtensions\Mapping\Driver\DriverInterface
     */
    private function getDriver($omDriver)
    {
        $driver = null;
        $className = get_class($omDriver);
        $driverName = substr($className, strrpos($className, '\\') + 1);
        if ($omDriver instanceof DriverChain ||
            $driverName == 'DriverChain' ||
            $driverName == 'MappingDriverChain'
        ) {
            $driver = new DriverChain();
            foreach ($omDriver->getDrivers() as $namespace => $nestedOmDriver) {
                $driver->addDriver($this->getDriver($nestedOmDriver), $namespace);
            }
        } else {
            $driverName = substr($driverName, 0, strpos($driverName, 'Driver'));
            // create driver instance
            $driverClassName = $this->extensionNamespace . '\Mapping\Driver\\' . $driverName;
            if (!class_exists($driverClassName)) {
                $driverClassName = $this->extensionNamespace . '\Mapping\Driver\Annotation';
                if (!class_exists($driverClassName)) {
                    throw new Exception\RuntimeException("Failed to fallback to annotation driver: ({$driverClassName}), extension driver was not found.");
                }
            }
            $driver = new $driverClassName();
            if (!$driver instanceof DriverInterface) {
                throw new Exception\RuntimeException(sprintf("Driver of class %s does not implement required FSi\DoctrineExtensions\Mapping\Driver\DriverInterface", get_class($driver)));
            }
            if ($driver instanceof AbstractFileDriver) {
                /** @var $driver FileDriver */
                $driver->setFileLocator($omDriver->getLocator());
            }
            if ($driver instanceof AbstractAnnotationDriver) {
                $driver->setAnnotationReader($this->annotationReader);
            }
        }
        return $driver;
    }
}
