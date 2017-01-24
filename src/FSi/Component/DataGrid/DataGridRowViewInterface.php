<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid;

interface DataGridRowViewInterface extends \Iterator, \Countable, \ArrayAccess
{
    /**
     * Return row index in DataGridView.
     *
     * @return int
     */
    public function getIndex();

    /**
     * Get the source object.
     *
     * @return mixed
     */
    public function getSource();
}
