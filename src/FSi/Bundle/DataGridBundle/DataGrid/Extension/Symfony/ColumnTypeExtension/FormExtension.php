<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnTypeExtension;

use Doctrine\Common\Util\ClassUtils;
use FSi\Bundle\DataGridBundle\Form\Type\RowType;
use FSi\Bundle\DataGridBundle\Form\Type\Symfony3RowType;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\DataGridInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class FormExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * Form Objects instances created by method CreateForm.
     *
     * @var array
     */
    protected $forms = array();

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function bindData(ColumnTypeInterface $column, $data, $object, $index)
    {
        if ($column->getOption('editable') === false) {
            return;
        }

        $formData = array();
        switch ($column->getId()) {
            case 'entity':
                $relationField = $column->getOption('relation_field');
                if (!isset($data[$relationField])) {
                    return;
                }

                $formData[$relationField] = $data[$relationField];
                break;

            default:
                $fieldMapping = $column->getOption('field_mapping');
                foreach ($fieldMapping as $field) {
                    if (!isset($data[$field])) {
                        return;
                    }

                    $formData[$field] = $data[$field];
                }
        }

        /** @var FormInterface $form */
        $form = $this->createForm($column, $index, $object);
        $form->submit(array($index => $formData));
        if ($form->isValid()) {
            $data = $form->getData();
            foreach ($data as $fields) {
                foreach ($fields as $field => $value) {
                    $column->getDataMapper()->setData($field, $object, $value);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        if (!$column->getOption('editable')) {
            return;
        }

        $data = $view->getSource();
        $index = $view->getAttribute('row');
        $form = $this->createForm($column, $index, $data);

        $view->setAttribute('form', $form->createView());
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'boolean',
            'number',
            'datetime',
            'entity',
            'gedmo_tree',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'editable' => false,
            'form_options' => array(),
            'form_type' => array(),
        ));

        $column->getOptionsResolver()->setAllowedTypes('editable', 'bool');
        $column->getOptionsResolver()->setAllowedTypes('form_options', 'array');
        $column->getOptionsResolver()->setAllowedTypes('form_type', 'array');
    }

    /**
     * Create Form Objects for column and rowset index.
     *
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param mixed $index
     * @param mixed $object
     * @return FormInterface
     */
    private function createForm(ColumnTypeInterface $column, $index, $object)
    {
        $formId = implode(array($column->getName(),$column->getId(), $index));
        if (array_key_exists($formId, $this->forms)) {
            return $this->forms[$formId];
        }

        //Create fields array. There are column types like entity where field_mapping
        //should not be used to build field array.
        $fields = array();
        switch ($column->getId()) {
            case 'entity':
                $field = array(
                    'name' => $column->getOption('relation_field'),
                    'type' => $this->isSymfony3() ? $this->getEntityTypeName() : 'entity',
                    'options' => array(),
                );

                $fields[$column->getOption('relation_field')] = $field;
                break;

            default:
                foreach ($column->getOption('field_mapping') as $fieldName) {
                    $field = array(
                        'name' => $fieldName,
                        'type' => null,
                        'options' => array(),
                    );
                    $fields[$fieldName] = $field;
                }
        }

        //Pass fields form options from column into $fields array.
        $fieldsOptions = $column->getOption('form_options');
        foreach ($fieldsOptions as $fieldName => $fieldOptions) {
            if (array_key_exists($fieldName, $fields)) {
                if (is_array($fieldOptions)) {
                    $fields[$fieldName]['options'] = $fieldOptions;
                }
            }
        }

        //Pass fields form type from column into $fields array.
        $fieldsTypes = $column->getOption('form_type');
        foreach ($fieldsTypes as $fieldName => $fieldType) {
            if (array_key_exists($fieldName, $fields)) {
                if (is_string($fieldType)) {
                    $fields[$fieldName]['type'] = $fieldType;
                }
            }
        }

        //Build data array, the data array holds data that should be passed into
        //form elements.
        switch ($column->getId()) {
            case 'datetime':
                foreach ($fields as &$field) {
                    $value = $column->getDataMapper()->getData($field['name'], $object);
                    if (!isset($field['type'])) {
                        $field['type'] = $this->isSymfony3()
                            ? $this->getDateTimeTypeName()
                            : 'datetime';
                    }
                    if (is_numeric($value) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'timestamp';
                    }
                    if (is_string($value) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'string';
                    }
                    if (($value instanceof \DateTime) && !isset($field['options']['input'])) {
                        $field['options']['input'] = 'datetime';
                    }
                }
                break;
        }

        if ($this->isSymfony3()) {
            $formBuilderOptions = array(
                'entry_type' => $this->getRowTypeName(),
                'csrf_protection' => false,
            );
        } else {
            $formBuilderOptions = array(
                'type' => new RowType($fields),
                'csrf_protection' => false,
            );
        }

        if ($this->isSymfony3()) {
            $formBuilderOptions['entry_options']['fields'] = $fields;
        }

        $formData = [];
        foreach (array_keys($fields) as $fieldName) {
            $formData[$fieldName] = $column->getDataMapper()->getData($fieldName, $object);
        }

        //Create form builder.
        $formBuilder = $this->formFactory->createNamedBuilder(
            $column->getDataGrid()->getName(),
            ($this->isSymfony3())
                ? $this->getCollectionTypeName()
                : 'collection',
            array($index => $formData),
            $formBuilderOptions
        );

        //Create Form.
        $this->forms[$formId] = $formBuilder->getForm();

        return $this->forms[$formId];
    }

    /**
     * @return string
     */
    private function getEntityTypeName()
    {
        return 'Symfony\Bridge\Doctrine\Form\Type\EntityType';
    }

    /**
     * @return string
     */
    private function getDateTimeTypeName()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\DateTimeType';
    }

    private function getCollectionTypeName()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\CollectionType';
    }

    /**
     * @return string
     */
    private function getRowTypeName()
    {
        return 'FSi\Bundle\DataGridBundle\Form\Type\Symfony3RowType';
    }

    /**
     * @return bool
     */
    private function isSymfony3()
    {
        return method_exists('Symfony\Component\Form\AbstractType', 'getBlockPrefix');
    }

}
