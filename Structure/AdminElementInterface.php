<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface AdminElementInterface extends ElementInterface
{
    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     */
    public function getDataGrid();

    /**
     * @return boolean
     */
    public function hasDataGrid();

    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     */
    public function getExportDataGrid();

    /**
     * @return boolean
     */
    public function hasExportDataGrid();

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     */
    public function getDataSource();

    /**
     * @return boolean
     */
    public function hasDataSource();

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     */
    public function getExportDataSource();

    /**
     * @return boolean
     */
    public function hasExportDataSource();

    /**
     * @param null $data
     * @return \Symfony\Component\Form\For|null
     */
    public function getForm($data = null);

    /**
     * @param null $data
     * @return boolean
     */
    public function hasForm($data = null);

    /**
     * @param null $data
     * @return \Symfony\Component\Form\For|null
     */
    public function getCreateForm($data = null);

    /**
     * @param null $data
     * @return boolean
     */
    public function hasCreateForm($data = null);

    /**
     * @param null $data
     * @return \Symfony\Component\Form\For|null
     */
    public function getEditForm($data = null);

    /**
     * @param null $data
     * @return boolean
     */
    public function hasEditForm($data = null);

    /**
     * @param DataGridFactoryInterface $factory
     * @return AdminElementInterface
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory);

    /**
     * @return DataGridFactoryInterface
     * @throws RuntimeException thrown when DataGridFactory is not set.
     */
    public function getDataGridFactory();

    /**
     * @param DataSourceFactoryInterface $factory
     * @return AdminElementInterface
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory);

    /**
     * @return DataSourceFactoryInterface
     * @throws RuntimeException thrown when DataSourceFactory is not set.
     */
    public function getDataSourceFactory();

    /**
     * @param FormFactoryInterface $factory
     * @return AdminElementInterface
     */
    public function setFormFactory(FormFactoryInterface $factory);

    /**
     * @return FormFactoryInterface
     * @throws RuntimeException thrown when FormFactoryInterface is not set.
     */
    public function getFormFactory();

    /**
     * This method is called from CRUDController after form validation is passed in edit and create action.
     * $entity came from form instance used in action.
     * Mostly this method should save updated object in database.
     *
     * @param mixed $entity
     */
    public function save($entity);

    /**
     * Method called after DataGrid update at listAction in CRUDController.
     * Mostly it should only call flush at ObjectManager.
     *
     * @return mixed
     */
    public function saveGrid();

    /**
     * This method is called from CRUDController in delete action.
     * $entity came from DataIndexer
     * Mostly this method should delete entity from database but it can be overwritten to
     * set flag instead of executing hard delete.
     *
     * @param mixed $entity
     */
    public function delete($entity);
}