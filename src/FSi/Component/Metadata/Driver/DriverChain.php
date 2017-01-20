<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\Metadata\Driver;

use FSi\Component\Metadata\Driver\DriverInterface;
use FSi\Component\Metadata\ClassMetadataInterface;

class DriverChain implements DriverInterface
{
    /**
     * Array of nested metadata drivers to iterate over. It's indexed by class namespaces.
     *
     * @var array
     */
    protected $drivers = array();

    /**
     * Accepts an array of DriverInterface instances indexed by class namespace
     *
     * @param \FSi\Component\Metadata\Driver\DriverInterface[] $drivers
     */
    public function __construct(array $drivers = array())
    {
        foreach ($drivers as $namespace => $driver) {
            $this->addDriver($driver, $namespace);
        }
    }

    /**
     * Add new driver to the chain
     *
     * @param \FSi\Component\Metadata\Driver\DriverInterface $driver
     * @param string $namespace
     * @return \FSi\Component\Metadata\Driver\DriverChain
     */
    public function addDriver(DriverInterface $driver, $namespace) {
        if (!isset($this->drivers[$namespace]))
            $this->drivers[$namespace] = array();
        $this->drivers[$namespace][] = $driver;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function loadClassMetadata(ClassMetadataInterface $metadata)
    {
        $className = $metadata->getClassName();
        foreach ($this->drivers as $namespace => $drivers) {
            if (strpos($className, $namespace) === 0) {
                foreach ($drivers as $driver)
                    $driver->loadClassMetadata($metadata);
            }
        }
    }
}
