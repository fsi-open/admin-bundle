<?php


namespace AdminPanel\Symfony\AdminBundle\Display\Property\Formatter;

use AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter;

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
     * @param string $true
     * @param string $false
     */
    public function __construct($true, $false)
    {
        $this->true = $true;
        $this->false = $false;
    }

    /**
     * @param mixed $value
     * @return mixed|string
     */
    public function format($value)
    {
        if (empty($value) && !is_bool($value)) {
            return $value;
        }

        return ($value) ? $this->true : $this->false;
    }
}
