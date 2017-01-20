<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\DoctrineExtensions\Mapping\Driver;

use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use FSi\Component\Metadata\Driver\DriverInterface as BaseDriverInterface;

interface DriverInterface extends BaseDriverInterface
{
    /**
     * Set metadata factory from the underlying ORM or ODM.
     *
     * @param \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory $metadataFactory
     * @return null
     */
    public function setBaseMetadataFactory(ClassMetadataFactory $metadataFactory);

    /**
     * Get associated metadata factory for underlying ORM/ODM.
     *
     * @return \Doctrine\Common\Persistence\Mapping\ClassMetadataFactory
     */
    public function getBaseMetadataFactory();
}
