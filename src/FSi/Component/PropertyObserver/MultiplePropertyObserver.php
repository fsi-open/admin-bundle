<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver;

class MultiplePropertyObserver extends PropertyObserver implements MultiplePropertyObserverInterface
{
    /**
     * {@inheritdoc}
     */
    public function saveValues($object, array $propertyPaths)
    {
        foreach ($propertyPaths as $propertyPath) {
            $this->saveValue($object, $propertyPath);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasChangedValues($object, array $propertyPaths, $notSavedAsNull = false)
    {
        $valueChanged = false;
        foreach ($propertyPaths as $propertyPath) {
            $valueChanged = $valueChanged || $this->hasChangedValue($object, $propertyPath, $notSavedAsNull);
        }
        return $valueChanged;
    }
}
