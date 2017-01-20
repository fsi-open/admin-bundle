<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver;

interface PropertyObserverInterface
{
    /**
     * Set new value of specified property and save it in internal storage.
     *
     * @param object $object
     * @param string $propertyPath
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function setValue($object, $propertyPath, $value);

    /**
     * Saves current value of specified property in internal storage.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     */
    public function saveValue($object, $propertyPath);

    /**
     * Returns true if value of specified property has not been ever saved.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function hasSavedValue($object, $propertyPath);

    /**
     * Returns previously saved value of specified property or throws exception if it has not been saved.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     * @throws \BadMethodCallException
     * @return mixed
     */
    public function getSavedValue($object, $propertyPath);

    /**
     * Sets previously saved value of specified property or throws exception if it has not been saved.
     *
     * @param object $object
     * @param string $propertyPath
     */
    public function resetValue($object, $propertyPath);

    /**
     * Returns true if previously saved value is different (in PHP strict sense) from the current one.
     *
     * @param object $object
     * @param string $propertyPath
     * @param bool $notSavedAsNull if true then property that was not previously saved is
     * treated as null, otherwise exception will be thrown if specified property path has not been saved
     * @throws \InvalidArgumentException
     * @return boolean
     *
     */
    public function hasChangedValue($object, $propertyPath, $notSavedAsNull = false);

    /**
     * Returns true if previously saved value is different (in PHP strict sense) from the current one.
     *
     * @param object $object
     * @param string $propertyPath
     * @throws \InvalidArgumentException
     * @return boolean
     * @deprecated will be removed in 1.0
     */
    public function hasValueChanged($object, $propertyPath);

    /**
     * Clears internally saved values of specified object
     *
     * @param object $object
     */
    public function remove($object);

    /**
     * Clears internally saved values of all objects
     */
    public function clear();
}
