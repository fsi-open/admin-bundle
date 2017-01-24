<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Driver;

/**
 * Factory for creating drivers.
 */
interface DriverFactoryInterface
{
    /**
     * Return driver type name.
     * For example if you are using Doctrine\DriverFactory this method will return 'doctrine' string.
     *
     * @return string
     */
    public function getDriverType();

    /**
     * @param array $options
     * @return \FSi\Component\DataSource\Driver\DriverInterface
     */
    public function createDriver($options = []);
}
