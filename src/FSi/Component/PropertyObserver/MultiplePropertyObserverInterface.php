<?php

declare(strict_types=1);

namespace FSi\Component\PropertyObserver;

interface MultiplePropertyObserverInterface extends PropertyObserverInterface
{
    /**
     * Saves current values of specified properties in internal storage.
     *
     * @param object $object
     * @param array $propertyPaths
     * @throws \InvalidArgumentException
     */
    public function saveValues($object, array $propertyPaths);

    /**
     * Returns true if any previously saved value of specified properties is different
     * (in PHP strict sense) from the current values.
     *
     * @param object $object
     * @param array $propertyPaths
     * @param bool $notSavedAsNull if true then property that was not previously saved is
     * treated as null, otherwise exception will be thrown if specified property path has not been saved
     * @throws \InvalidArgumentException
     * @return boolean
     */
    public function hasChangedValues($object, array $propertyPath, $notSavedAsNull = false);
}
