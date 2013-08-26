<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCRUD extends AbstractElement implements CRUDInterface ,DataGridAwareInterface, DataSourceAwareInterface,
    FormAwareInterface
{
    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    protected $datasourceFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridInterface
     */
    protected $datagrid;

    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    protected $datasource;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $editForm;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $createForm;

    /**
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_crud_list';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'allow_delete' => true,
            'template_crud_list' => null,
            'template_crud_create' => null,
            'template_crud_edit' => null,
            'template_crud_delete' => null
        ));

        $resolver->setAllowedTypes(array(
            'allow_delete' => 'bool',
            'template_crud_list' => array('null', 'string'),
            'template_crud_create' => array('null', 'string'),
            'template_crud_edit' => array('null', 'string'),
            'template_crud_delete' => array('null', 'string'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->datagridFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->datasourceFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->formFactory = $factory;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGrid()
    {
        if (!$this->hasDataGrid()) {
            return null;
        }

        return $this->datagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDataGrid()
    {
        if (!isset($this->datagrid)) {
            $this->datagrid = $this->initDataGrid($this->datagridFactory);
            if ($this->options['allow_delete']) {
                if (!$this->datagrid->hasColumnType('batch')) {
                    $this->datagrid->addColumn('batch', 'batch', array('display_order' => -1000));
                }
            }
        }

        return isset($this->datagrid);
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource()
    {
        if (!$this->hasDataSource()) {
            return null;
        }

        return $this->datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function hasDataSource()
    {
        if (!isset($this->datasource)) {
            $this->datasource = $this->initDataSource($this->datasourceFactory);
        }

        return isset($this->datasource);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreateForm()
    {
        if (!$this->hasCreateForm()) {
            return null;
        }

        return $this->createForm;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCreateForm()
    {
        if (!isset($this->createForm)) {
            $this->createForm = $this->initCreateForm($this->formFactory);
        }

        return isset($this->createForm);
    }

    /**
     * {@inheritdoc}
     */
    public function getEditForm($data = null)
    {
        if (!$this->hasEditForm($data)) {
            return null;
        }

        return $this->editForm;
    }

    /**
     * {@inheritdoc}
     */
    public function hasEditForm($data = null)
    {
        if (!isset($this->editForm)) {
            $this->editForm = $this->initEditForm($this->formFactory, $data);
        }

        return isset($this->editForm);
    }

    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return null|\FSi\Component\DataGrid\DataGridInterface
     */
    protected function initDataGrid(DataGridFactoryInterface $factory)
    {
        return null;
    }

    /**
     * Initialize DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return null|\FSi\Component\DataSource\DataSourceInterface
     */
    protected function initDataSource(DataSourceFactoryInterface $factory)
    {
        return null;
    }

    /**
     * Initialize create Form. This form will be used in createAction in CRUDController.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @return null|\Symfony\Component\Form\FormInterface
     */
    protected function initCreateForm(FormFactoryInterface $factory)
    {
        return null;
    }

    /**
     * Initialize edit Form. This form will be used in editAction in CRUDController.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param mixed $data
     * @return null|\Symfony\Component\Form\FormInterface
     */
    protected function initEditForm(FormFactoryInterface $factory, $data = null)
    {
        return null;
    }
}