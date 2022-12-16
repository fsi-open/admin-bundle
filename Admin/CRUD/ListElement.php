<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;

/**
 * @template T of array<string,mixed>|object
 * @template-extends DataIndexerElement<T>
 */
interface ListElement extends DataIndexerElement
{
    public function createDataGrid(): DataGridInterface;

    public function setDataGridFactory(DataGridFactoryInterface $factory): void;

    /**
     * @return DataSourceInterface<T>
     */
    public function createDataSource(): DataSourceInterface;

    public function setDataSourceFactory(DataSourceFactoryInterface $factory): void;

    public function saveDataGrid(): void;
}
