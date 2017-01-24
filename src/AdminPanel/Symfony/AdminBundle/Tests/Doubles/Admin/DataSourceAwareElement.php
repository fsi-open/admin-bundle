<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\Tests\Doubles\Admin;

use AdminPanel\Symfony\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
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
