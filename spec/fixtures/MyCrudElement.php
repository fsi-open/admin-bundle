<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\spec\fixtures;

use FSi\Bundle\AdminBundle\Doctrine\Admin\CRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class MyCrudElement extends CRUDElement
{
    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'FSiDemoBundle:Entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'my_entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin.my_entity.name';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null)
    {
    }
}