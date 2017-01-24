<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Factory;

use AdminPanel\Symfony\AdminBundle\Admin\Element;

interface Worker
{
    /**
     * Mount something to admin element.
     *
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @return mixed
     */
    public function mount(Element $element);
}
