<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter;

class DateTime implements ValueFormatter
{
    /**
     * @var string
     */
    private $format;

    /**
     * @param string $format
     */
    public function __construct($format = 'Y-m-d H:i:s')
    {
        $this->format = $format;
    }

    /**
     * @param mixed $value
     * @return mixed|string
     */
    public function format($value)
    {
        if (empty($value) || !$value instanceof \DateTime) {
            return $value;
        }

        return $value->format($this->format);
    }
}
