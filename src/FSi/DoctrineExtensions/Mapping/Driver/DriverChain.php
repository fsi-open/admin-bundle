<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use FSi\Component\Metadata\Driver\DriverChain as BaseDriverChain;
use FSi\DoctrineExtensions\Mapping\Exception\RuntimeException;

class DriverChain extends BaseDriverChain implements DriverInterface
{
    /**
     * @var \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory
     */
    private $baseMetadataFactory;

    /**
     * {@inheritdoc}
     */
    public function setBaseMetadataFactory(ClassMetadataFactory $metadataFactory)
    {
        foreach ($this->drivers as $drivers) {
            foreach ($drivers as $driver) {
                $driver->setBaseMetadataFactory($metadataFactory);
            }
        }
        $this->baseMetadataFactory = $metadataFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getBaseMetadataFactory()
    {
        if (!isset($this->baseMetadataFactory)) {
            throw new RuntimeException('Required base metadata factory has not been set on this driver.');
        }
        return $this->baseMetadataFactory;
    }
}
