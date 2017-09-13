<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataIndexer\DataIndexerInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MyCRUD extends GenericCRUDElement
{
    public function getId(): string
    {
        return 'my_crud';
    }

    public function getDataIndexer(): DataIndexerInterface
    {
    }

    public function save($object): void
    {
    }

    public function saveDataGrid(): void
    {
    }

    public function delete($object): void
    {
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
        return $factory->createDataGrid('my_datagrid');
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
        return $factory->createDataSource('doctrine', ['entity' => 'FSiDemoBundle:MyEntity'], 'my_datasource');
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
        return $factory->create('form');
    }
}
