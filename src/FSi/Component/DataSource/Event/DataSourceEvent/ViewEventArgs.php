<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event\DataSourceEvent;

use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\DataSourceViewInterface;

/**
 * Event class for DataSource.
 */
class ViewEventArgs extends DataSourceEventArgs
{
    /**
     * @var \FSi\Component\DataSource\DataSourceViewInterface
     */
    private $view;

    /**
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     * @param \FSi\Component\DataSource\DataSourceViewInterface $view
     */
    public function __construct(DataSourceInterface $datasource, DataSourceViewInterface $view)
    {
        parent::__construct($datasource);
        $this->view = $view;
    }

    /**
     * @return \FSi\Component\DataSource\DataSourceViewInterface
     */
    public function getView()
    {
        return $this->view;
    }
}
