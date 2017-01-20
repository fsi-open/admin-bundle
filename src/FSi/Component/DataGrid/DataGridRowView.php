<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Exception\UnexpectedTypeException;

class DataGridRowView implements DataGridRowViewInterface
{
    /**
     * Cells views.
     *
     * @var array
     */
    protected $cellViews = array();

    /**
     * The source object for which view is created.
     *
     * @var mixed
     */
    protected $source;

    /**
     * @var int
     */
    protected $index;

    /**
     * @param DataGridViewInterface $dataGridView
     * @param array $columns
     * @param mixed $source
     * @param int $index
     * @throws Exception\UnexpectedTypeException
     */
    public function __construct(DataGridViewInterface $dataGridView, array $columns, $source, $index)
    {
        $this->source = $source;
        $this->index = $index;
        foreach ($columns as $name => $column) {
            if (!$column instanceof ColumnTypeInterface) {
                throw new UnexpectedTypeException('Column object must implement FSi\Component\DataGrid\Column\ColumnTypeInterface');
            }

            $cellView = $column->createCellView($this->source, $index);
            $cellView->setDataGridView($dataGridView);

            $this->cellViews[$name] = $cellView;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Returns the number of cells in the row.
     * Implementation of Countable::count().
     *
     * @return int
     */
    public function count()
    {
        return count($this->cellViews);
    }

    /**
     * Return the current cell view.
     * Similar to the current() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return \FSi\Component\DataGrid\Column\CellViewInterface current element from the rowset
     */
    public function current()
    {
        return current($this->cellViews);
    }

    /**
     * Return the identifying key of the current column.
     * Similar to the key() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return string
     */
    public function key()
    {
        return key($this->cellViews);
    }

    /**
     * Move forward to next cell view.
     * Similar to the next() function for arrays in PHP.
     * Required by interface Iterator.
     *
     * @return string
     */
    public function next()
    {
        next($this->cellViews);
    }

    /**
     * Rewind the Iterator to the first element.
     * Similar to the reset() function for arrays in PHP.
     * Required by interface Iterator.
     */
    public function rewind()
    {
        reset($this->cellViews);
    }

    /**
     * Checks if current position is valid.
     * Required by the SeekableIterator implementation.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->key() !== null;
    }

    /**
     * Required by the ArrayAccess implementation.
     *
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->cellViews[$offset]);
    }

    /**
     * Required by the ArrayAccess implementation
     *
     * @param string $offset
     * @throws \InvalidArgumentException
     * @return ColumnTypeInterface
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->cellViews[$offset];
        }

        throw new \InvalidArgumentException(sprintf('Column "%s" does not exist in row.', $offset));
    }

    /**
     * Does nothing.
     * Required by the ArrayAccess implementation.
     *
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
    }

    /**
     * Does nothing.
     * Required by the ArrayAccess implementation.
     *
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
    }
}
