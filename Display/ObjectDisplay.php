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

class ObjectDisplay implements Display
{
    /**
     * @var mixed
     */
    private $object;

    /**
     * @var Property[]
     */
    private $properties;

    /**
     * @param $object
     * @throws \InvalidArgumentException
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException("Argument used to create ObjectDisplay must be an object.");
        }

        $this->object = $object;
        $this->properties = array();
    }

    /**
     * {@inheritdoc}
     */
    public function add(Property $property)
    {
        $this->properties[] = $property;

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
     * @param $accessor
     * @param $property
     * @return mixed
     */
    private function getPropertyValue($accessor, $property)
    {
        $value = $accessor->getValue($this->object, $property->getPath());
        foreach ($property->getValueDecorators() as $decorator) {
            $value = $decorator->decorate($value);
        }

        return $value;
    }
}
