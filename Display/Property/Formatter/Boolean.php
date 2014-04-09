<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

class Boolean implements ValueFormatter
{
    /**
     * @var string
     */
    private $true;

    /**
     * @var string
     */
    private $false;

    /**
     * @param $true
     * @param $false
     */
    public function __construct($true, $false)
    {
        $this->true = $true;
        $this->false = $false;
    }

    public function format($value)
    {
        if (empty($value) && !is_bool($value)) {
            return $value;
        }

        return ($value) ? $this->true : $this->false;
    }
}