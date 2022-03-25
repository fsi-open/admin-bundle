<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display;

use InvalidArgumentException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class PropertyAccessDisplay implements Display
{
    /**
     * @var array<int,Property>
     */
    private array $data = [];

    /**
     * @var object|array<string,mixed>
     */
    private $object;

    private PropertyAccessorInterface $accessor;

    /**
     * @param object|array<string,mixed> $object
     */
    public function __construct($object)
    {
        $this->validateObject($object);

        $this->object = $object;
        $this->accessor = $this->createPropertyAccessor();
    }

    public function add($path, ?string $label = null, array $valueFormatters = []): Display
    {
        $this->data[] = new Property(
            $this->accessor->getValue($this->object, $path),
            $label,
            $valueFormatters
        );

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param mixed $object
     * @return void
     */
    private function validateObject($object): void
    {
        if (false === is_object($object) && false === is_array($object)) {
            throw new InvalidArgumentException(sprintf(
                'Argument used to create "%s" must be an object or an array, got "%s" instead.',
                get_class($this),
                gettype($object)
            ));
        }
    }

    private function createPropertyAccessor(): PropertyAccessorInterface
    {
        $accessorBuilder = PropertyAccess::createPropertyAccessorBuilder();
        $accessorBuilder->enableMagicCall();

        return $accessorBuilder->getPropertyAccessor();
    }
}
