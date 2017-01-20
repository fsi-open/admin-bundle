<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
