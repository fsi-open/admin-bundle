<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

class DateTime implements ValueFormatter
{
    /**
     * @var string
     */
    private $format;

    public function __construct($format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    public function format($value)
    {
        if (empty($value) || !$value instanceof \DateTime) {
            return $value;
        }

        return $value->format($this->format);
    }
}