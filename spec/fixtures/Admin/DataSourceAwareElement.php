<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class DataSourceAwareElement extends SimpleAdminElement implements DataSourceAwareInterface
{
    private $dataSourceFactory;

    /**
     * @param \FSi\Component\DataGrid\DataSourceFactoryInterface $factory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->dataSourceFactory = $factory;
    }
}
