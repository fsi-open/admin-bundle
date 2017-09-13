<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\Element;

interface Worker
{
    public function mount(Element $element): void;
}
