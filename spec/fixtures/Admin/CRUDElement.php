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

    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->dataGridFactory = $factory;
    }

    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->dataSourceFactory = $factory;
    }

    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->formFactory = $factory;
    }

    public function isFormAware()
    {
        return isset($this->formFactory);
    }

    public function isDataGridAware()
    {
        return isset($this->dataGridFactory);
    }

    public function isDataSourceAware()
    {
        return isset($this->dataSourceFactory);
    }
}
