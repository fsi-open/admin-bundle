<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\View;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class ObjectDisplay implements Display
{
    /**
     * @var mixed
     */
    private $object;

    /**
     * @var \FSi\Bundle\AdminBundle\Display\Property[]
     */
    private $properties;

    /**
     * @param object $object
     * @throws \InvalidArgumentException
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException("Argument used to create ObjectDisplay must be an object.");
        }

        $this->object = $object;
        $this->properties = [];
    }

    /**
     * {@inheritdoc}
     */
    public function add($path, $label = null, $valueFormatters = [])
    {
        $this->properties[] = new Property($path, $label, $valueFormatters);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function createView()
    {
        $accessor = $this->createPropertyAccessor();
        $view = new DisplayView();
        foreach ($this->properties as $property) {
            $view->add(
                new View(
                    $this->getPropertyValue($accessor, $property),
                    $property->getPath(),
                    $property->getLabel()
                )
            );
        }

        return $view;
    }

    /**
     * @return \Symfony\Component\PropertyAccess\PropertyAccessorInterface
     */
    private function createPropertyAccessor()
    {
        $accessorBuilder = PropertyAccess::createPropertyAccessorBuilder();
        $accessorBuilder->enableMagicCall();

        return $accessorBuilder->getPropertyAccessor();
    }

    /**
     * @param \Symfony\Component\PropertyAccess\PropertyAccessorInterface $accessor
     * @param \FSi\Bundle\AdminBundle\Display\Property $property
     * @return mixed
     */
    private function getPropertyValue(PropertyAccessorInterface $accessor, Property $property)
    {
        $value = $accessor->getValue($this->object, $property->getPath());
        foreach ($property->getValueFormatters() as $formatter) {
            $value = $formatter->format($value);
        }

        return $value;
    }
}
