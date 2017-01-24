<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display;

interface Display
{
    /**
     * @param string $path
     * @param string|null $label
     * @param array|\AdminPanel\Symfony\AdminBundle\Display\Property\ValueFormatter[] $valueFormatters
     * @return \AdminPanel\Symfony\AdminBundle\Display\Display
     */
    public function add($path, $label = null, $valueFormatters = []);

    /**
     * @return \AdminPanel\Symfony\AdminBundle\Display\DisplayView
     */
    public function createView();
}
