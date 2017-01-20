<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid;

use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridEventInterface;
use Symfony\Component\EventDispatcher\Event;

class DataGridEvent extends Event implements DataGridEventInterface
{
    /**
     * @var FSi\Component\DataGrid\DataGridInterface
     */
    protected $dataGrid;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @param \FSi\Component\DataGrid\DataGridInterface $dataGrid
     * @param mixed $data
     */
    public function __construct(DataGridInterface $dataGrid, $data)
    {
        $this->dataGrid = $dataGrid;
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        $this->data = $data;
    }
}
