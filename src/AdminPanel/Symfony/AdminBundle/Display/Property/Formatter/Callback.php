<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter;

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
