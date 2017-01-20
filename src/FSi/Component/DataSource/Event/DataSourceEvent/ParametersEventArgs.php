<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Event\DataSourceEvent;

use FSi\Component\DataSource\DataSourceInterface;

/**
 * Event class for DataSource.
 */
class ParametersEventArgs extends DataSourceEventArgs
{
    /**
     * @var array
     */
    private $parameters;

    /**
     * @param \FSi\Component\DataSource\DataSourceInterface $datasource
     * @param array $parameters
     */
    public function __construct(DataSourceInterface $datasource, $parameters)
    {
        parent::__construct($datasource);
        $this->setParameters($parameters);
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
