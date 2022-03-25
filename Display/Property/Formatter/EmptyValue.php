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

class EmptyValue implements ValueFormatter
{
    private string $emptyValue;

    public function __construct(string $emptyValue = '-')
    {
        $this->emptyValue = $emptyValue;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function format($value)
    {
        if (empty($value)) {
            return $this->emptyValue;
        }

        return $value;
    }
}
