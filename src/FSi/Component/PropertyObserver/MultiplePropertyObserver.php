<?php

declare(strict_types=1);

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
