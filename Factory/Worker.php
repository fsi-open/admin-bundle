<?php

namespace FSi\Bundle\AdminBundle\Factory;

use FSi\Bundle\AdminBundle\Admin\Element;

interface Worker
{
    /**
     * Mount something to admin element.
     *
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     * @return mixed
     */
    public function mount(Element $element);
}
