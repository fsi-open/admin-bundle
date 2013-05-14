<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContext;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class CrudListContext extends AbstractContext
{
    /**
     * @var DataSourceInterface
     */
    protected $datasource;

    /**
     * @var DataGridInterface
     */
    protected $datagrid;

    /**
     * @param DataGridInterface $datagrid
     * @return $this
     */
    public function setDataGrid(DataGridInterface $datagrid)
    {
        $this->datagrid = $datagrid;

        return $this;
    }

    /**
     * @return DataGridInterface
     */
    public function getDataGrid()
    {
        return $this->datagrid;
    }

    /**
     * @param DataSourceInterface $datasource
     * @return $this
     */
    public function setDataSource(DataSourceInterface $datasource)
    {
        $this->datasource = $datasource;

        return $this;
    }

    /**
     * @return DataSourceInterface
     */
    public function getDataSource()
    {
        return $this->datasource;
    }
}