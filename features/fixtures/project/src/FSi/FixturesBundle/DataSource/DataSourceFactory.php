<?php

declare(strict_types=1);

namespace FSi\FixturesBundle\DataSource;

use FSi\Component\DataSource\DataSourceFactory as BaseDataSourceFactory;

class DataSourceFactory extends BaseDataSourceFactory
{
    public function clearDatasource($datasource)
    {
        unset($this->datasources[$datasource]);
    }
}
