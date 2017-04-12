<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class GenericListElement extends AbstractElement implements ListElement
{
    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    protected $datasourceFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_list';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'template_list' => null,
        ]);

        $resolver->setAllowedTypes('template_list', ['null', 'string']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->datagridFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->datasourceFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataGrid()
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
            throw new RuntimeException('initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface');
        }

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataSource()
    {
        $datasource = $this->initDataSource($this->datasourceFactory);

        if (!is_object($datasource) || !$datasource instanceof DataSourceInterface) {
            throw new RuntimeException('initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface');
        }

        return $datasource;
    }

    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    abstract protected function initDataGrid(DataGridFactoryInterface $factory);

    /**
     * Initialize DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    abstract protected function initDataSource(DataSourceFactoryInterface $factory);
}
