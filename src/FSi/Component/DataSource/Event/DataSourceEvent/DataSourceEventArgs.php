<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event\DataSourceEvent;

use Symfony\Component\EventDispatcher\Event;
use FSi\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class DataSourceEventArgs extends Event
{
    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    private $datasource;

    /**
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     */
    public function __construct(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;
    }

    /**
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->datasource;
    }
}
