<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template T of array<string,mixed>|object
 * @template-implements ListElement<T>
 */
abstract class GenericListElement extends AbstractElement implements ListElement
{
    protected DataSourceFactoryInterface $datasourceFactory;

    protected DataGridFactoryInterface $datagridFactory;

    public function getRoute(): string
    {
        return 'fsi_admin_list';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'template_list' => null,
        ]);

        $resolver->setAllowedTypes('template_list', ['null', 'string']);
    }

    public function setDataGridFactory(DataGridFactoryInterface $factory): void
    {
        $this->datagridFactory = $factory;
    }

    public function setDataSourceFactory(DataSourceFactoryInterface $factory): void
    {
        $this->datasourceFactory = $factory;
    }

    public function createDataGrid(): DataGridInterface
    {
        return $this->initDataGrid($this->datagridFactory);
    }

    public function createDataSource(): DataSourceInterface
    {
        return $this->initDataSource($this->datasourceFactory);
    }

    abstract protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface;

    /**
     * @param DataSourceFactoryInterface $factory
     * @return DataSourceInterface<T>
     */
    abstract protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface;
}
