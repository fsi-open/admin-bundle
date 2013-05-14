<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Context\DataIO;

use FSi\Bundle\AdminBundle\Context\AbstractContextBuilder;
use FSi\Bundle\AdminBundle\Context\DataIO\ExportContext;
use FSi\Bundle\AdminBundle\Exception\MissingDataGridException;
use FSi\Bundle\AdminBundle\Exception\MissingDataSourceException;
use FSi\Bundle\AdminBundle\Structure\AdminElementInterface;
use FSi\Bundle\AdminBundle\Structure\ElementInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
class ExportContextBuilder extends AbstractContextBuilder
{
    /**
     * {@inheritdoc}
     */
    public static function supports(ElementInterface $element)
    {
        return $element instanceof AdminElementInterface;
    }

    /**
     * @return \FSi\Bundle\AdminBundle\Context\DataIO\ExportContext
     */
    public function buildContext()
    {
        $context = new ExportContext();

        //Pre set DataGrid
        $context->setDatagrid($this->createDataGrid());
        //Post set DataGrid

        //Pre set DataSource
        $context->setDatasource($this->createDataSource());
        //Post set DataSource

        return $context;
    }

    /**
     * @return \FSi\Component\DataGrid\DataGridInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingDataGridException
     */
    protected function createDataGrid()
    {
        if (!$this->getElement()->hasExportDataGrid()) {
            throw new MissingDataGridException(sprintf('Admin object with id: "%s" doesnt have export DataGrid.', $this->getElement()->getId()));
        }

        return  $this->getElement()->getExportDataGrid();
    }

    /**
     * @return \FSi\Component\DataSource\DataSourceInterface
     * @throws \FSi\Bundle\AdminBundle\Exception\MissingDataSourceException
     */
    protected function createDataSource()
    {
        if (!$this->getElement()->hasExportDataSource()) {
            throw new MissingDataSourceException(sprintf('Admin object with id: "%s" doesnt have export DataSource.', $this->getElement()->getId()));
        }

        return  $this->getElement()->getExportDataSource();
    }
}