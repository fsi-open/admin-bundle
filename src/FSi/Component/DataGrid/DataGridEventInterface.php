<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid;

interface DataGridEventInterface
{
    /**
     * Returns the form at the source of the event.
     *
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    public function getDataGrid();

    /**
     * Returns the data associated with this event.
     *
     * @return mixed
     */
    public function getData();

    /**
     * Allows updating data for example if you need to filter values
     *
     * @param mixed $data
     */
    public function setData($data);
}
