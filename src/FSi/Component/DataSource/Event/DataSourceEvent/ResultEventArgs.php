<?php

declare(strict_types=1);

namespace FSi\Component\DataSource\Event\DataSourceEvent;

use FSi\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class ResultEventArgs extends DataSourceEventArgs
{
    /**
     * @var mixed
     */
    private $result;

    /**
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     * @param mixed $result
     */
    public function __construct(DataSourceInterface $datasource, $result)
    {
        parent::__construct($datasource);
        $this->setResult($result);
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}
