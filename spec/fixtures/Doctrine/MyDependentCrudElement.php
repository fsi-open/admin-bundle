<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Doctrine;

use FSi\Bundle\AdminBundle\Doctrine\Admin\DependentCRUDElement;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class MyDependentCrudElement extends DependentCRUDElement
{
    public function getClassName(): string
    {
        return 'FSiDemoBundle:Entity';
    }

    public function getId(): string
    {
        return 'my_entity';
    }

    public function getParentId(): string
    {
        return 'my_parent_entity';
    }

    protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface
    {
    }

    protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface
    {
    }

    protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface
    {
    }
}
