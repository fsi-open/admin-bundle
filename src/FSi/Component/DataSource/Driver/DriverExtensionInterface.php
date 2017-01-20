<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Driver;

/**
 * Extension of driver.
 */
interface DriverExtensionInterface
{
    /**
     * Returns types of extended drivers
     *
     * return array
     */
    public function getExtendedDriverTypes();

    /**
     * Checks whether given extension has field for given type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldType($type);

    /**
     * Returns field for given type, or, if can't fine one, throws exception.
     *
     * @param string $type
     * @return \FSi\Component\DataSource\Field\FieldTypeInterface
     */
    public function getFieldType($type);

    /**
     * Checks whether given extension has any extension for given field type.
     *
     * @param string $type
     * @return bool
     */
    public function hasFieldTypeExtensions($type);

    /**
     * Returns collection of extensions for given field type.
     *
     * @param string $type
     * @return \Traversable
     */
    public function getFieldTypeExtensions($type);

    /**
     * Loads events subscribers.
     *
     * Each subscriber must implements \Symfony\Component\EventDispatcher\EventSubscriberInterface.
     *
     * @return array
     */
    public function loadSubscribers();
}
