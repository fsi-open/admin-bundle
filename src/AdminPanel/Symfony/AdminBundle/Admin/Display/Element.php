<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Admin\Display;

use AdminPanel\Symfony\AdminBundle\Admin\Element as BaseElement;

interface Element extends BaseElement
{
    /**
     * @param mixed $object
     * @return \AdminPanel\Symfony\AdminBundle\Display\Display
     */
    public function createDisplay($object);

    /**
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer();
}
