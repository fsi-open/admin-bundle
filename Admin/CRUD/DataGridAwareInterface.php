<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Component\DataGrid\DataGridFactoryInterface;

/**
 * @deprecated Deprecated since version 1.1, to be removed in 1.2. Use
 *             FSi\Bundle\AdminBundle\Admin\CRUD\ListElement instead.
 */
interface DataGridAwareInterface
{
    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory);
}