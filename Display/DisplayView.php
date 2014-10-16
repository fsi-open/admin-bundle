<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Display;

use FSi\Bundle\AdminBundle\Display\Property\View;

class DisplayView implements \IteratorAggregate
{
    /**
     * @var View[]
     */
    protected $properties;

    public function __construct()
    {
        $this->properties = array();
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Display\Property\View $propertyView
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
