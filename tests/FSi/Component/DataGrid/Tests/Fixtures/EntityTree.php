<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
