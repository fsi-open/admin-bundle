<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Tests\Fixtures;

class EntityTree
{
    public $id;
    public $left = 'left';
    public $right = 'right';
    public $root = 'root';
    public $level = 'level';
    public $parent;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function getParent()
    {
        if (!isset($this->parent)) {
            $this->parent = new EntityTree("bar");
        }

        return $this->parent;
    }
}
