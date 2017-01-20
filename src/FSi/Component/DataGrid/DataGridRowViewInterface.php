<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
