<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

class Collection implements ValueFormatter
{
    /**
     * @var array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[]
     */
    private $formatters;

    /**
     * @param array|\FSi\Bundle\AdminBundle\Display\Property\ValueFormatter[] $formatters
     */
    public function __construct(array $formatters)
    {
        $this->formatters = $formatters;
    }

    /**
     * @param mixed $value
     * @return array|mixed
     */
    public function format($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (!is_array($value) && !$value instanceof \Iterator) {
            throw new \InvalidArgumentException("Collection decorator require value to be an array or implement \\Iterator");
        }

        $formatted = array();
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
