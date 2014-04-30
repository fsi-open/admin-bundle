<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;

class DataGridAwareElement extends SimpleAdminElement implements DataGridAwareInterface
{
    private $dataGridFactory;

    /**
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->dataGridFactory = $factory;
    }
}
