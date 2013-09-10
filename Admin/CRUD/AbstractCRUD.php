<?php

/**
 * (c) Fabryka Stron Internetowych sp. z o.o <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
    protected $form;

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
            'allow_add' => true,
            'allow_edit' => true,
            'crud_list_title' => 'crud.list.title',
            'crud_create_title' => 'crud.create.title',
            'crud_edit_title' => 'crud.edit.title',
            'template_crud_list' => null,
            'template_crud_create' => null,
            'template_crud_edit' => null,
            'template_crud_delete' => null
        ));

        $resolver->setAllowedTypes(array(
            'allow_delete' => 'bool',
            'allow_add' => 'bool',
            'allow_edit' => 'bool',
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
        if (!isset($this->datagrid)) {
            $datagrid = $this->initDataGrid($this->datagridFactory);

            if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
                throw new RuntimeException('initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface');
            }

            if ($this->options['allow_delete']) {
                if (!$datagrid->hasColumnType('batch')) {
                    $datagrid->addColumn('batch', 'batch', array('display_order' => -1000));
                }
            }

            $this->datagrid = $datagrid;
        }

        return $this->datagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataSource()
    {
        if (!isset($this->datasource)) {
            $datasource = $this->initDataSource($this->datasourceFactory);

            if (!is_object($datasource) || !$datasource instanceof DataSourceInterface) {
                throw new RuntimeException('initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface');
            }

            $this->datasource = $datasource;
        }

        return $this->datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm($data = null)
    {
        if (!isset($this->form)) {
            $form = $this->initForm($this->formFactory, $data);

            if (!is_object($form) || !$form instanceof FormInterface) {
                throw new RuntimeException('initForm should return instanceof Symfony\\Component\\Form\\FormInterface');
            }

            $this->form = $form;
        }

        return $this->form;
    }

    /**
     * Initialize DataGrid.
     *
     * @param \FSi\Component\DataGrid\DataGridFactoryInterface $factory
     * @return \FSi\Component\DataGrid\DataGridInterface
     */
    abstract protected function initDataGrid(DataGridFactoryInterface $factory);

    /**
     * Initialize DataSource.
     *
     * @param \FSi\Component\DataSource\DataSourceFactoryInterface $factory
     * @return \FSi\Component\DataSource\DataSourceInterface
     */
    abstract protected function initDataSource(DataSourceFactoryInterface $factory);

    /**
     * Initialize create Form. This form will be used in createAction in CRUDController.
     *
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     * @param null $data
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null);
}