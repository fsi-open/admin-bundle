<?php


namespace AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use FSi\Component\DataGrid\DataGridFactoryInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement instead.
 */
interface DataGridAwareInterface
{
    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory);
}