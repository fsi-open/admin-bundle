<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
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
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Norbert Orzechowicz <norbert@fsi.pl>
 */
abstract class AbstractCRUD extends AbstractElement implements CRUDElement
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
     * {@inheritdoc}
     */
    public function getRoute()
    {
        return 'fsi_admin_list';
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRoute()
    {
        return $this->getRoute();
    }

    /**
     * {@inheritdoc}
     */
    public function getSuccessRouteParameters()
    {
        return $this->getRouteParameters();
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'allow_delete' => true,
            'allow_add' => true,
            'template_crud_list' => null,
            'template_crud_create' => null,
            'template_crud_edit' => null,
            'template_list' => function (Options $options) {
                return $options['template_crud_list'];
            },
            'template_form' => function (Options $options) {
                return $options['template_crud_edit'];
            }
        ]);

        $resolver->setNormalizer('template_crud_create', function (Options $options, $value) {
            if ($value !== $options['template_crud_edit']) {
                throw new RuntimeException(
                    'CRUD admin element options "template_crud_create" and "template_crud_edit" have both to have the same value'
                );
            }

            return $value;
        });

        $resolver->setAllowedTypes('allow_delete', 'bool');
        $resolver->setAllowedTypes('allow_add', 'bool');
        $resolver->setAllowedTypes('template_crud_list', ['null', 'string']);
        $resolver->setAllowedTypes('template_crud_create', ['null', 'string']);
        $resolver->setAllowedTypes('template_crud_edit', ['null', 'string']);
        $resolver->setAllowedTypes('template_list', ['null', 'string']);
        $resolver->setAllowedTypes('template_form', ['null', 'string']);
    }

    /**
     * @inheritdoc
     */
    public function apply($object)
    {
        $this->delete($object);
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
    public function createDataGrid()
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (!is_object($datagrid) || !$datagrid instanceof DataGridInterface) {
            throw new RuntimeException('initDataGrid should return instanceof FSi\\Component\\DataGrid\\DataGridInterface');
        }

        if ($this->options['allow_delete']) {
            if (!$datagrid->hasColumnType('batch')) {
                $datagrid->addColumn('batch', 'batch', [
                    'actions' => [
                        'delete' => [
                            'route_name' => 'fsi_admin_batch',
                            'additional_parameters' => ['element' => $this->getId()],
                            'label' => 'crud.list.batch.delete'
                        ]
                    ],
                    'display_order' => -1000
                ]);
            }
        }

        return $datagrid;
    }

    /**
     * {@inheritdoc}
     */
    public function createDataSource()
    {
        $datasource = $this->initDataSource($this->datasourceFactory);

        if (!is_object($datasource) || !$datasource instanceof DataSourceInterface) {
            throw new RuntimeException('initDataSource should return instanceof FSi\\Component\\DataSource\\DataSourceInterface');
        }

        return $datasource;
    }

    /**
     * {@inheritdoc}
     */
    public function createForm($data = null)
    {
        $form = $this->initForm($this->formFactory, $data);

        if (!is_object($form) || !$form instanceof FormInterface) {
            throw new RuntimeException('initForm should return instanceof Symfony\\Component\\Form\\FormInterface');
        }

        return $form;
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
     * @param mixed $data
     * @return \Symfony\Component\Form\FormInterface
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null);
}
