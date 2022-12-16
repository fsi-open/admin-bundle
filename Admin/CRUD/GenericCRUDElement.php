<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Admin\AbstractElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactoryInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\Exception\DataGridColumnException;
use FSi\Component\DataSource\DataSourceFactoryInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @template T of array<string,mixed>|object
 * @template TSaveDTO of array<string,mixed>|object
 * @template-default TSaveDTO=T
 * @template-implements CRUDElement<T, TSaveDTO>
 */
abstract class GenericCRUDElement extends AbstractElement implements CRUDElement
{
    protected DataSourceFactoryInterface $datasourceFactory;

    protected DataGridFactoryInterface $datagridFactory;

    protected FormFactoryInterface $formFactory;

    public function getRoute(): string
    {
        return 'fsi_admin_list';
    }

    public function getSuccessRoute(): string
    {
        return $this->getRoute();
    }

    public function getSuccessRouteParameters(): array
    {
        return $this->getRouteParameters();
    }

    public function configureOptions(OptionsResolver $resolver): void
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
                    'CRUD admin element options "template_crud_create" and "template_crud_edit" have both to have '
                        . 'the same value'
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

    public function apply($object): void
    {
        $this->delete($object);
    }

    public function setDataGridFactory(DataGridFactoryInterface $factory): void
    {
        $this->datagridFactory = $factory;
    }

    public function setDataSourceFactory(DataSourceFactoryInterface $factory): void
    {
        $this->datasourceFactory = $factory;
    }

    public function setFormFactory(FormFactoryInterface $factory): void
    {
        $this->formFactory = $factory;
    }

    public function createDataGrid(): DataGridInterface
    {
        $datagrid = $this->initDataGrid($this->datagridFactory);

        if (true === $this->getOption('allow_delete') && false === $datagrid->hasColumnType('batch')) {
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

        return $datagrid;
    }

    /**
     * @return DataSourceInterface<T>
     */
    public function createDataSource(): DataSourceInterface
    {
        return $this->initDataSource($this->datasourceFactory);
    }

    public function createForm($data = null): FormInterface
    {
        return $this->initForm($this->formFactory, $data);
    }

    abstract protected function initDataGrid(DataGridFactoryInterface $factory): DataGridInterface;

    /**
     * @param DataSourceFactoryInterface $factory
     * @return DataSourceInterface<T>
     */
    abstract protected function initDataSource(DataSourceFactoryInterface $factory): DataSourceInterface;

    /**
     * Initialize form. This form will be used in create and update actions.
     *
     * @param FormFactoryInterface $factory
     * @param T $data
     * @return FormInterface<string,FormInterface>
     */
    abstract protected function initForm(FormFactoryInterface $factory, $data = null): FormInterface;
}
