<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property\Decorator;

use FSi\Bundle\AdminBundle\Display\Property\ValueDecorator;

class DateTime implements ValueDecorator
{
    /**
     * @var string
     */
    private $format;

    public function __construct($format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    public function decorate($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (!$value instanceof \DateTime) {
            throw new \InvalidArgumentException("DateTime decorator require value to be an instance of \\DateTime");
        }

        return $value->format($this->format);
    }
}