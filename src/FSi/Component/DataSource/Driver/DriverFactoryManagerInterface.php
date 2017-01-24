<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver;

interface DriverFactoryManagerInterface
{
    /**
     * @param \FSi\Component\DataSource\Driver\DriverFactoryInterface $factory
     */
    public function addFactory(DriverFactoryInterface $factory);

    /**
     * @param string $driverType
     * @return null|\FSi\Component\DataSource\Driver\DriverFactoryInterface
     */
    public function getFactory($driverType);

    /**
     * @param string $driverType
     * @return bool
     */
    public function hasFactory($driverType);
}
