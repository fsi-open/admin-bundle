<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
     * @var string
     */
    private $label;

    /**
     * @param mixed $value
     * @param string $label
     * @param array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[] $valueFormatters
     */
    public function __construct($value, $label, array $valueFormatters = [])
    {
        $this->validateLabel($label);
        $this->validateFormatters($valueFormatters);

        $this->value = $this->formatValue($value, $valueFormatters);
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getLabel()
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
     * @param ValueFormatter[] $valueFormatters
     * @return mixed
     */
    private function formatValue($value, array $valueFormatters)
    {
        foreach ($valueFormatters as $formatter) {
            $value = $formatter->format($value);
        }

        return $value;
    }

    /**
     * @param string $label
     * @throws InvalidArgumentException
     */
    private function validateLabel($label)
    {
        if (!is_string($label)) {
            throw new InvalidArgumentException(sprintf(
                'Property label must be a string, got "%s"',
                gettype($label)
            ));
        }
    }

    /**
     * @param ValueFormatter[] $valueFormatters
     * @throws InvalidArgumentException
     */
    private function validateFormatters(array $valueFormatters)
    {
        foreach ($valueFormatters as $formatter) {
            if (!$formatter instanceof ValueFormatter) {
                throw new InvalidArgumentException(sprintf(
                    'Expected property formatter to be an instance of'
                    . ' FSi\Bundle\AdminBundle\Display\Property\ValueFormatter,'
                    . ' got "%s" instead',
                    is_object($formatter) ? get_class($formatter) : gettype($formatter)
                ));
            }
        }
    }
}
