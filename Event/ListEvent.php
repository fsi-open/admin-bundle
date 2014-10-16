<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use Symfony\Component\HttpFoundation\Request;

class ListEvent extends AdminEvent
{
    /**
     * @var \FSi\Component\DataSource\DataSource
     */
    protected $dataSource;

    /**
     * @var \FSi\Component\DataGrid\DataGrid
     */
    protected $dataGrid;

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @param \FSi\Component\DataGrid\DataGrid $dataGrid
     */
    public function __construct(Element $element, Request $request, DataSource $dataSource, DataGrid $dataGrid)
    {
        parent::__construct($element, $request);
        $this->dataSource = $dataSource;
        $this->dataGrid = $dataGrid;
    }

    /**
     * @return \FSi\Component\DataSource\DataSource
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @return \FSi\Component\DataGrid\DataGrid
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }
}
