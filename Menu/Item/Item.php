<?php

namespace FSi\Bundle\AdminBundle\Menu\Item;

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
     * @param string|null $name
     */
    public function __construct($name = null)
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
     * @param Item $item
     */
    public function addChild(Item $item)
    {
        $this->children[] = $item;
    }

    /**
     * @return bool
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
