<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

class DisplayView implements \IteratorAggregate
{
    /**
     * @var PropertyView[]
     */
    protected $properties;

    public function __construct()
    {
        $this->properties = array();
    }

    /**
     * @param PropertyView $propertyView
     */
    public function add(PropertyView $propertyView)
    {
        $this->properties[] = $propertyView;
    }

    /**
     * @return \ArrayIterator|Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->properties);
    }
}
