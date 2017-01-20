<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function createDriver($options = array());
}
