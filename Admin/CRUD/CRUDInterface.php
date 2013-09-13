<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
interface CRUDInterface
{
    /**
     * @return \FSi\Component\DataGrid\DataGrid|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataGrid();

    /**
     * @return \FSi\Component\DataSource\DataSource|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createDataSource();

    /**
     * @param mixed $data
     * @return \Symfony\Component\Form\Form|null
     * @throws \FSi\Bundle\AdminBundle\Exception\RuntimeException
     */
    public function createForm($data = null);

    /**
     * This method should be used inside of admin objects to retrieve DataIndexerInterface.
     *
     * @return \FSi\Component\DataIndexer\DataIndexerInterface
     */
    public function getDataIndexer();

    /**
     * This method is called from CRUDController after form validation is passed in edit and create action.
     * Mostly this method should save updated object in database.
     *
     * @param mixed $object
     */
    public function save($object);

    /**
     * Method called after DataGrid update at listAction in CRUDController.
     * Mostly it should only call flush at ObjectManager.
     *
     * @return mixed
     */
    public function saveDataGrid();

    /**
     * This method is called from CRUDController in delete action.
     * Mostly this method should delete entity from database but it can be overwritten to
     * set flag instead of executing hard delete.
     *
     * @param mixed $object
     */
    public function delete($object);
}
