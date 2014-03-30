<?php

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;

interface Worker
{
    /**
     * Mount something to admin element.
     *
     * @param ElementInterface $element
     * @return mixed
     */
    public function mount(ElementInterface $element);
}
