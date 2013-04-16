<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Structure;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractAdminElement extends AbstractElement implements AdminElementInterface
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridFactoryInterface
     */
    protected $datagridFactory;

    /**
     * @var \FSi\Component\DataSource\DataSourceFactoryInterface
     */
    protected $datasourceFactory;

    /**
     * @var \FSi\Component\DataGrid\DataGridInterface
     */
    protected $datagrid;

    /**
     * @var \FSi\Component\DataGrid\DataGridInterface
     */
    protected $exportDatagrid;

    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    protected $datasource;

    /**
     * @var \FSi\Component\DataSource\DataSourceInterface
     */
    protected $exportDatasource;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

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
    public function getBaseRouteName()
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
            'template_crud_edit' => null
        ));

        $resolver->setAllowedTypes(array(
            'allow_delete' => 'bool',
            'template_crud_list' => array('null', 'string'),
            'template_crud_create' => array('null', 'string'),
            'template_crud_edit' => array('null', 'string'),
        ));
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
            $this->datagrid = $this->initDataGrid();
        }

        return isset($this->datagrid);
    }

    /**
     * {@inheritdoc}
     */
    public function getExportDataGrid()
    {
        if (!$this->hasExportDataGrid()) {
            return null;
        }

        return $this->exportDatagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function hasExportDataGrid()
    {
        if (!isset($this->exportDatagrid)) {
            $this->exportDatagrid = $this->initExportDataGrid();
        }

        return isset($this->exportDatagrid);
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
            $this->datasource = $this->initDataSource();
        }

        return isset($this->datasource);
    }

    /**
     * {@inheritdoc}
     */
    public function getExportDataSource()
    {
        if (!$this->hasExportDataSource()) {
            return null;
        }

        $this->exportDatasource->setMaxResults(null);
        return $this->exportDatasource;
    }

    /**
     * {@inheritdoc}
     */
    public function hasExportDataSource()
    {
        if (!isset($this->exportDatasource)) {
            $this->exportDatasource = $this->initExportDataSource();
        }

        return isset($this->exportDatasource);
    }

    /**
     * {@inheritdoc}
     */
    public function getForm($data = null)
    {
        if (!$this->hasForm($data)) {
            return null;
        }

        return $this->initForm($data);
    }

    /**
     * {@inheritdoc}
     */
    public function hasForm($data = null)
    {
        if (!isset($this->form)) {
            $this->form = $this->initForm($data);
        }

        return isset($this->form);
    }

    /**
     * {@inheritdoc}
     */
    public function getCreateForm($data = null)
    {
        if (!$this->hasCreateForm($data)) {
            return null;
        }

        return $this->initCreateForm($data);
    }

    /**
     * {@inheritdoc}
     */
    public function hasCreateForm($data = null)
    {
        if (!isset($this->createForm)) {
            $this->createForm = $this->initCreateForm($data);

            if (!isset($this->createForm)) {
                $this->createForm = ($this->hasForm($data))
                    ? $this->form
                    : null;
            }
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

        return $this->initEditForm($data);
    }

    /**
     * {@inheritdoc}
     */
    public function hasEditForm($data = null)
    {
        if (!isset($this->editForm)) {
            $this->editForm = $this->initEditForm($data);

            if (!isset($this->editForm)) {
                $this->editForm = ($this->hasForm($data))
                    ? $this->form
                    : null;
            }
        }

        return isset($this->editForm);
    }

    /**
     * {@inheritdoc}
     */
    public function setDataGridFactory(DataGridFactoryInterface $factory)
    {
        $this->datagridFactory = $factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataGridFactory()
    {
        if (!isset($this->datagridFactory)) {
            throw new RuntimeException("DataGridFactory doesn't exists.");
        }

        return $this->datagridFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setDataSourceFactory(DataSourceFactoryInterface $factory)
    {
        $this->datasourceFactory = $factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSourceFactory()
    {
        if (!isset($this->datasourceFactory)) {
            throw new RuntimeException("DataSourceFactory doesn't exists.");
        }

        return $this->datasourceFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function setFormFactory(FormFactoryInterface $factory)
    {
        $this->formFactory = $factory;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormFactory()
    {
        if (!isset($this->formFactory)) {
            throw new RuntimeException("FormFactory doesn't exists.");
        }

        return $this->formFactory;
    }

    /**
     * Initialize DataGrid.
     * DataGrid must be created by DataGridFactory.
     * To access DataGridFactory you can use method $this->getDataGridFactory();
     *
     * @return null|DataGridInterface
     */
    protected function initDataGrid()
    {
        return null;
    }

    /**
     * Initialize DataGrid.
     * DataGrid must be created by DataGridFactory.
     * To access DataGridFactory you can use method $this->getDataGridFactory();
     * Its important to not use columns like actions and editable option in ExportDataGrid.
     * Using editable option may cause memory limit problems.
     *
     * @return null|DataGridInterface
     */
    protected function initExportDataGrid()
    {
        return null;
    }

    /**
     * Initialize DataSource.
     * DataGrid must be created by DataSourceFactory.
     * To access DataSourceFactory you can use method $this->getDataSourceFactory();
     *
     * @return null|DataSource
     */
    protected function initDataSource()
    {
        return null;
    }

    /**
     * Initialize DataSource.
     * DataGrid must be created by DataSourceFactory.
     * To access DataSourceFactory you can use method $this->getDataSourceFactory();
     *
     * @return null|DataSource
     */
    protected function initExportDataSource()
    {
        return null;
    }

    /**
     * Initialize Form.
     * Form be created by FormFactory.
     * To access FormFactory you can use method $this->getFormFactory();
     *
     * @param mixed $data
     * @return null|FormInterface
     */
    protected function initForm($data = null)
    {
        return null;
    }

    /**
     * Initialize create Form. This form will be used in createAction in CRUDController.
     * Form be created by FormFactory.
     * To access FormFactory you can use method $this->getFormFactory();
     *
     * @param mixed $data
     * @return null|FormInterface
     */
    protected function initCreateForm($data = null)
    {
        return $this->initForm($data);
    }

    /**
     * Initialize edit Form. This form will be used in editAction in CRUDController.
     * Form be created by FormFactory.
     * To access FormFactory you can use method $this->getFormFactory();
     *
     * @param mixed $data
     * @return null|FormInterface
     */
    protected function initEditForm($data = null)
    {
        return $this->initForm($data);
    }

    /**
     * This method will create edit and delete action options and return them as
     * array ready to use in DataGridColumn.
     *
     * @param \FSi\Component\DataGrid\DataGridInterface $datagrid
     * @return array
     */
    abstract protected function getDataGridActionColumnOptions(DataGridInterface $datagrid);
}