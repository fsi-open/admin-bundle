<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin;

use Doctrine\Common\Persistence\ManagerRegistry;
use FSi\Bundle\AdminBundle\Admin\CRUD\DataGridAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\DataSourceAwareInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormAwareInterface;
use FSi\Bundle\AdminBundle\Doctrine\Admin\DoctrineAwareInterface;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;

class ElementFactory
{
    /**
     * @var DataGridFactoryInterface
     */
    protected $dataGridFactory;

    /**
     * @var DataSourceFactoryInterface
     */
    protected $dataSourceFactory;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @param string $class
     * @return ElementInterface
     * @throws \InvalidArgumentException
     */
    public function create($class)
    {
        $element = new $class;
        if (!$element instanceof ElementInterface) {
            throw new \InvalidArgumentException(sprintf("%s does not seems to be an admin element.", $class));
        }

        if ($element instanceof DataGridAwareInterface) {
            $element->setDataGridFactory($this->dataGridFactory);
        }

        if ($element instanceof DataSourceAwareInterface) {
            $element->setDataSourceFactory($this->dataSourceFactory);
        }

        if ($element instanceof FormAwareInterface) {
            $element->setFormFactory($this->formFactory);
        }

        if ($element instanceof DoctrineAwareInterface) {
            $element->setManagerRegistry($this->managerRegistry);
        }

        return $element;
    }

    /**
     * @param DataGridFactoryInterface $dataGridFactory
     */
    public function setDataGridFactory(DataGridFactoryInterface $dataGridFactory)
    {
        $this->dataGridFactory = $dataGridFactory;
    }

    /**
     * @param DataSourceFactoryInterface $dataSourceFactory
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $dataSourceFactory)
    {
        $this->dataSourceFactory = $dataSourceFactory;
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param ManagerRegistry $registry
     */
    public function setManagerRegistry(ManagerRegistry $registry)
    {
        $this->managerRegistry = $registry;
    }
}
