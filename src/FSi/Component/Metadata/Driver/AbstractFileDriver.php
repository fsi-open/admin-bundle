<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Driver;

use Doctrine\Common\Persistence\Mapping\Driver\FileLocator;
use FSi\Component\Metadata\ClassMetadataInterface;

abstract class AbstractFileDriver implements DriverInterface
{
    /**
     * FileLocator used to locate file containing class metadata
     *
     * @var \Doctrine\Common\Persistence\Mapping\Driver\FileLocator
     */
    private $locator;

    /**
     * Set file locator that will be used to locate class metadata file
     *
     * @param \Doctrine\Common\Persistence\Mapping\Driver\FileLocator $locator
     * @return \FSi\Component\Metadata\Driver\DriverInterface
     */
    public function setFileLocator(FileLocator $locator)
    {
        $this->locator = $locator;
        return $this;
    }

    /**
     * Return previously set file locator or throws an exception
     *
     * @throws \RuntimeException
     * @return \Doctrine\Common\Persistence\Mapping\Driver\FileLocator
     */
    public function getFileLocator()
    {
        if (!isset($this->locator)) {
            throw new \RuntimeException('Required file locator has not been set on the file driver.');
        }
        return $this->locator;
    }

    /**
     * Returns path of the file containing class matadata.
     *
     * This method shout be used in loadClassMetadata to reach metadata file.
     *
     * @param \FSi\Component\Metadata\ClassMetadataInterface $metadata
     * @return \Doctrine\Common\Persistence\Mapping\Driver\FileLocator
     */
    protected function findMappingFile(ClassMetadataInterface $metadata)
    {
        return $this->getFileLocator()->findMappingFile($metadata->getClassName());
    }
}
