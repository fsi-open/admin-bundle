<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\PropertyObserver;

use Symfony\Component\PropertyAccess\PropertyAccess;

class PropertyObserver implements PropertyObserverInterface
{
    /**
     * Internal value storage
     *
     * @var array
     */
    protected $savedValues = array();

    /**
     * @var \Symfony\Component\PropertyAccess\PropertyAccessor
     */
    protected $propertyAccessor;

    /**
     * Constructs new PropertyObserver
     */
    public function __construct()
    {
        $this->propertyAccessor = PropertyAccess::createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($object, $propertyPath, $value)
    {
        $this->validateObject($object);
        $this->propertyAccessor->setValue($object, $propertyPath, $value);
        $this->saveValue($object, $propertyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function saveValue($object, $propertyPath)
    {
        $this->validateObject($object);
        $oid = spl_object_hash($object);
        if (!isset($this->savedValues[$oid])) {
            $this->savedValues[$oid] = array();
        }
        $this->savedValues[$oid][$propertyPath] = $this->propertyAccessor->getValue($object, $propertyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function hasSavedValue($object, $propertyPath)
    {
        $this->validateObject($object);
        $oid = spl_object_hash($object);
        return isset($this->savedValues[$oid]) && array_key_exists($propertyPath, $this->savedValues[$oid]);
    }

    /**
     * {@inheritdoc}
     */
    public function getSavedValue($object, $propertyPath)
    {
        $this->validateObject($object);

        $oid = spl_object_hash($object);
        if (!isset($this->savedValues[$oid]) || !array_key_exists($propertyPath, $this->savedValues[$oid])) {
            throw new Exception\BadMethodCallException(sprintf('Value of property "%s" from specified object was not saved previously', $propertyPath));
        }

        return $this->savedValues[$oid][$propertyPath];
    }

    /**
     * {@inheritdoc}
     */
    public function resetValue($object, $propertyPath)
    {
        $this->propertyAccessor->setValue(
            $object,
            $propertyPath,
            $this->getSavedValue($object, $propertyPath)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function hasChangedValue($object, $propertyPath, $notSavedAsNull = false)
    {
        $this->validateObject($object);

        $currentValue = $this->propertyAccessor->getValue($object, $propertyPath);

        if ($notSavedAsNull && !$this->hasSavedValue($object, $propertyPath)) {
            return isset($currentValue);
        }

        return ($this->getSavedValue($object, $propertyPath) !== $currentValue);
    }

    /**
     * {@inheritdoc}
     */
    public function hasValueChanged($object, $propertyPath)
    {
        return $this->hasChangedValue($object, $propertyPath);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($object)
    {
        $this->validateObject($object);

        $oid = spl_object_hash($object);
        unset($this->savedValues[$oid]);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->savedValues = array();
    }

    /**
     * @param $object
     * @throws Exception\InvalidArgumentException
     */
    protected function validateObject($object)
    {
        if (!is_object($object)) {
            throw new Exception\InvalidArgumentException('Only object\'s properties could be observed by PropertyObserver');
        }
    }
}
