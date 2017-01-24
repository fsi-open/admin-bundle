<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter;

class EmptyValue implements ValueFormatter
{
    /**
     * @var string
     */
    private $emptyValue;

    /**
     * @param string $emptyValue
     */
    public function __construct($emptyValue = '-')
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
