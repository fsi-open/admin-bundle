<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display\Property\Formatter;

use FSi\Bundle\AdminBundle\Display\Property\ValueFormatter;

class Callback implements ValueFormatter
{
    /**
     * @var \Closure
     */
    private $closure;

    /**
     * @param callable $closure
     */
    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function format($value)
    {
        if (empty($value)) {
            return $value;
        }

        $closure = $this->closure;

        return $closure($value);
    }
}
