<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyAccessDisplay implements Display
{
    /**
     * @var Property[]
     */
    private $data = [];

    /**
     * @var object|array
     */
    private $object;

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    /**
     * @param object|array $object
     */
    public function __construct($object)
    {
        $this->validateObject($object);

        $this->object = $object;
        $this->accessor = $this->createPropertyAccessor();
    }

    /**
     * {@inheritdoc}
     */
    public function add($path, $label, array $valueFormatters = array())
    {
        $this->data[] = new Property(
            $this->accessor->getValue($this->object, $path),
            $label,
            $valueFormatters
        );

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param object|array $object
     * @throws InvalidArgumentException
     */
    private function validateObject($object)
    {
        if (!is_object($object) && !is_array($object)) {
            throw new InvalidArgumentException(sprintf(
                'Argument used to create "%s" must be an object or an array, got "%s" instead.',
                get_class($this),
                gettype($object)
            ));
        }
    }

    /**
     * @return PropertyAccessorInterface
     */
    private function createPropertyAccessor()
    {
        $accessorBuilder = PropertyAccess::createPropertyAccessorBuilder();
        $accessorBuilder->enableMagicCall();

        return $accessorBuilder->getPropertyAccessor();
    }
}
