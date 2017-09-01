<?php

namespace FSi\FixturesBundle\Admin;

use FSi\Bundle\AdminBundle\Doctrine\Admin\ListElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;

class CustomSubscriber extends ListElement
{
    public function getId()
    {
        return 'custom_subscriber';
    }

    public function getClassName()
    {
        return 'FSi\FixturesBundle\Entity\Subscriber';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        return  $factory->createDataGrid(Subscriber::ID);
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        return $factory->createDataSource(
            'doctrine-orm',
            ['entity' => $this->getClassName()],
            Subscriber::ID
        );
    }
}
