<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;
use InvalidArgumentException;

class Collection implements ValueFormatter
{
    /**
     * @var array<ValueFormatter>
     */
    private array $formatters;

    /**
     * @param array<ValueFormatter> $formatters
     */
    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * @param iterable<int|string,mixed>|null $value
     * @return array<int|string,mixed>|null
     */
    public function format($value)
    {
        if (null === $value) {
            return $value;
        }

        if (false === is_iterable($value)) {
            throw new InvalidArgumentException(sprintf(
                'Collection formatter requires value to be iterable, %s given',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        $formatted = [];
        foreach ($value as $key => $val) {
            $formattedValue = $val;
            foreach ($this->formatters as $formatter) {
                $formattedValue = $formatter->format($formattedValue);
            }

            $formatted[$key] = $formattedValue;
        }

        return $formatted;
    }
}
