<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Menu;

use FSi\Bundle\AdminBundle\Admin\Element;

class Item
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Item[]
     */
    private $children;

    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Element
     */
    private $element;

    /**
     * @param string|null $name
     * @throws \InvalidArgumentException
     */
    public function __construct($name)
    {
        $this->children = array();
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     */
    public function setElement(Element $element)
    {
        $this->element = $element;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Admin\Element
     */
    public function getElement()
    {
        return $this->element;
    }

    /**
     * @return bool
     */
    public function hasElement()
    {
        return isset($this->element);
    }

    /**
     * @param Item $item
     */
    public function addChild(Item $item)
    {
        $this->children[] = $item;
    }

    /**
     * @return int
     */
    public function hasChildren()
    {
        return (boolean) count($this->children);
    }

    /**
     * @return Item[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }
}
