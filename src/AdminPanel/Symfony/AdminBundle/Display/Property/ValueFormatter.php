<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display\Property;

interface ValueFormatter
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function format($value);
}
