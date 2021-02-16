<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;
use InvalidArgumentException;

class Property
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * @var string|null
     */
    private $label;

    /**
     * @param mixed $value
     * @param string|null $label
     * @param array<ValueFormatter> $valueFormatters
     */
    public function __construct($value, ?string $label = null, array $valueFormatters = [])
    {
        $this->validateFormatters($valueFormatters);

        $this->value = $this->formatValue($value, $valueFormatters);
        $this->label = $label;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @param array<ValueFormatter> $valueFormatters
     * @return mixed
     */
    private function formatValue($value, array $valueFormatters)
    {
        foreach ($valueFormatters as $formatter) {
            $value = $formatter->format($value);
        }

        return $value;
    }

    private function validateFormatters(array $valueFormatters): void
    {
        foreach ($valueFormatters as $formatter) {
            if (false === $formatter instanceof ValueFormatter) {
                throw new InvalidArgumentException(sprintf(
                    'Expected property formatter to be an instance of %s, got "%s" instead',
                    ValueFormatter::class,
                    is_object($formatter) ? get_class($formatter) : gettype($formatter)
                ));
            }
        }
    }
}
