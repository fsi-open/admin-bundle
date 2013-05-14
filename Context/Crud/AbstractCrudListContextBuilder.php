<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\Crud;

use FSi\Bundle\AdminBundle\Context\AbstractContextBuilder;
use FSi\Bundle\AdminBundle\Exception\MissingDataGridException;
use FSi\Bundle\AdminBundle\Exception\MissingDataSourceException;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCrudListContextBuilder extends AbstractContextBuilder
{
    /**
     * {@inheritdoc}
     */
    public function buildContext()
    {
        $template = $this->getElement()->hasOption('template_crud_list')
            ? $this->getElement()->getOption('template_crud_list')
            : null;

        $context = new CrudListContext($template);

        // Pre Create DataGrid
        $context->setDataGrid($this->createDataGrid());
        // Post Create DataGrid

        // Pre Create DataSource
        $context->setDataSource($this->createDataSource());
        // Post Create DataSource

        return $context;
    }

    /**
     * @return \FSi\Component\DataGrid\DataGridInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingDataGridException
     */
    protected function createDataGrid()
    {
        if (!$this->getElement()->hasDataGrid()) {
            throw new MissingDataGridException(sprintf('Admin object with id: "%s" doesnt have DataGrid.', $this->getElement()->getId()));
        }

        return  $this->getElement()->getDataGrid();
    }

    /**
     * @return \FSi\Component\DataSource\DataSourceInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingDataSourceException
     */
    protected function createDataSource()
    {
        if (!$this->getElement()->hasDataSource()) {
            throw new MissingDataSourceException(sprintf('Admin object with id: "%s" doesnt have DataSource.', $this->getElement()->getId()));
        }

        return  $this->getElement()->getDataSource();
    }
}