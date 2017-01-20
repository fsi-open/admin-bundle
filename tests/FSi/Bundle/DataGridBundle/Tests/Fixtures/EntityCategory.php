<?php

namespace FSi\Bundle\DataGridBundle\Tests\Fixtures;

class EntityCategory
{
    public $id;
    public $name;

    public function __construct($id, $name)
    {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
}
