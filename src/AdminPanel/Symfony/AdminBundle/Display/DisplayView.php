<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Display;

use AdminPanel\Symfony\AdminBundle\Display\Property\View;

class DisplayView implements \IteratorAggregate
{
    /**
     * @var View[]
     */
    protected $properties;

    public function __construct()
    {
        $this->properties = [];
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Display\Property\View $propertyView
     */
    public function add(View $propertyView)
    {
        $this->properties[] = $propertyView;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }
}
