<?php


namespace AdminPanel\Symfony\AdminBundle\Event;

use AdminPanel\Symfony\AdminBundle\Admin\Element;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\HttpFoundation\Request;

class ListEvent extends AdminEvent
{
    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    protected $dataSource;

    /**
     * @var \FSi\Component\DataGrid\DataGridInterface
     */
    protected $dataGrid;

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FSi\Component\DataSource\DataSourceInterface $dataSource
     * @param \FSi\Component\DataGrid\DataGridInterface $dataGrid
     */
    public function __construct(Element $element, Request $request, DataSourceInterface $dataSource, DataGridInterface $dataGrid)
    {
        parent::__construct($element, $request);
        $this->dataSource = $dataSource;
        $this->dataGrid = $dataGrid;
    }

    /**
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    public function getDataGrid()
    {
        return $this->dataGrid;
    }
}
