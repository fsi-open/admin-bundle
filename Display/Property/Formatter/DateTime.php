<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use DateTimeInterface;
use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

class DateTime implements ValueFormatter
{
    private string $format;

    public function __construct(string $format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    /**
     * @param mixed $value
     * @return mixed|string
     */
    public function format($value)
    {
        if (empty($value) || false === $value instanceof DateTimeInterface) {
            return $value;
        }

        return $value->format($this->format);
    }
}
