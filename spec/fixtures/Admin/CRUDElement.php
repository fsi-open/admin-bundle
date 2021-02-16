<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\spec\fixtures\Admin;

use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class CRUDElement extends SimpleAdminElement
{
    private $dataGridFactory;
    private $dataSourceFactory;
    private $formFactory;

    public function setDataGridFactory(DataGridFactoryInterface $factory): void
    {
        $this->dataGridFactory = $factory;
    }

    public function setDataSourceFactory(DataSourceFactoryInterface $factory): void
    {
        $this->dataSourceFactory = $factory;
    }

    public function setFormFactory(FormFactoryInterface $factory): void
    {
        $this->formFactory = $factory;
    }

    public function isFormAware(): bool
    {
        return isset($this->formFactory);
    }

    public function isDataGridAware(): bool
    {
        return isset($this->dataGridFactory);
    }

    public function isDataSourceAware(): bool
    {
        return isset($this->dataSourceFactory);
    }
}
