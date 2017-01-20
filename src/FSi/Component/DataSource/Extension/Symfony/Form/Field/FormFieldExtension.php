<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataSource\Extension\Symfony\Form\Field;

use FSi\Component\DataSource\Field\FieldAbstractExtension;
use FSi\Component\DataSource\Field\FieldTypeInterface;
use FSi\Component\DataSource\DataSourceInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;
use FSi\Component\DataSource\Event\FieldEvents;
use FSi\Component\DataSource\Event\FieldEvent;

/**
 * Fields extension.
 */
class FormFieldExtension extends FieldAbstractExtension
{
    /**
     * @var \Symfony\Component\Form\FormFactory
     */
    protected $formFactory;

    /**
     * @var array
     */
    protected $forms = array();

    /**
     * Original values of input parameters for each supported field.
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FieldEvents::PRE_BIND_PARAMETER => array('preBindParameter'),
            FieldEvents::POST_BUILD_VIEW => array('postBuildView'),
            FieldEvents::POST_GET_PARAMETER => array('preGetParameter'),
        );
    }

    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    public function __construct(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return array('text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
        $field->getOptionsResolver()
            ->setDefaults(array(
                'form_filter' => true,
                'form_options' => array(),
                'form_from_options' => array(),
                'form_to_options' =>array()
            ))
            ->setDefined(array(
                'form_type',
                'form_order'
            ))
            ->setAllowedTypes('form_filter', 'bool')
            ->setAllowedTypes('form_options', 'array')
            ->setAllowedTypes('form_from_options', 'array')
            ->setAllowedTypes('form_to_options', 'array')
            ->setAllowedTypes('form_order', 'integer')
            ->setAllowedTypes('form_type', 'string')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function postBuildView(FieldEvent\ViewEventArgs $event)
    {
        $field = $event->getField();
        $view = $event->getView();

        if ($form = $this->getForm($field)) {
            $view->setAttribute('form', $form->createView());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preBindParameter(FieldEvent\ParameterEventArgs $event)
    {
        $field = $event->getField();
        $field_oid = spl_object_hash($field);
        $parameter = $event->getParameter();

        if (!$form = $this->getForm($field)) {
            return;
        }

        if ($form->isSubmitted()) {
            $form = $this->getForm($field, true);
        }

        $datasourceName = $field->getDataSource() ? $field->getDataSource()->getName() : null;

        if (empty($datasourceName)) {
            return;
        }

        if (isset($parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$field->getName()])) {
            $dataToBind = array(
                DataSourceInterface::PARAMETER_FIELDS => array(
                    $field->getName() => $parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$field->getName()],
                ),
            );
            $this->parameters[$field_oid] = $parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$field->getName()];

            $form->submit($dataToBind);
            $data = $form->getData();

            if (isset($data[DataSourceInterface::PARAMETER_FIELDS][$field->getName()])) {
                $parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$field->getName()] = $data[DataSourceInterface::PARAMETER_FIELDS][$field->getName()];
            } else {
                unset($parameter[$datasourceName][DataSourceInterface::PARAMETER_FIELDS][$field->getName()]);
            }

            $event->setParameter($parameter);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preGetParameter(FieldEvent\ParameterEventArgs $event)
    {
        $field = $event->getField();
        $field_oid = spl_object_hash($field);

        $datasourceName = $field->getDataSource() ? $field->getDataSource()->getName() : null;
        if (isset($this->parameters[$field_oid])) {
            $parameters = array(
                $datasourceName => array(
                    DataSourceInterface::PARAMETER_FIELDS => array(
                        $field->getName() => $this->parameters[$field_oid]
                    )
                )
            );
            $event->setParameter($parameters);
        }
    }

    /**
     * Builds form.
     *
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @param bool $force
     * @return \Symfony\Component\Form\Form
     */
    protected function getForm(FieldTypeInterface $field, $force = false)
    {
        if (!$datasource = $field->getDataSource()) {
            return;
        }

        if (!$field->getOption('form_filter')) {
            return;
        }

        $field_oid = spl_object_hash($field);

        if (isset($this->forms[$field_oid]) && !$force) {
            return $this->forms[$field_oid];
        }

        $options = $field->getOption('form_options');
        $options = array_merge($options, array('required' => false, 'auto_initialize' => false));

        $form = $this->formFactory->createNamed($datasource->getName(), 'collection', null, array('csrf_protection' => false));
        $fieldsForm = $this->formFactory->createNamed(DataSourceInterface::PARAMETER_FIELDS, 'form', null, array('auto_initialize' => false));

        switch ($field->getComparison()) {
            case 'between':
                $this->buildBetweenComparisonForm($fieldsForm, $field, $options);
                break;

            case 'isNull':
                $this->buildIsNullComparisonForm($fieldsForm, $field, $options);
                break;

            default:
                $type = $field->hasOption('form_type') ? $field->getOption('form_type') : $field->getType();

                switch ($type) {
                    case 'boolean':
                        $this->buildBooleanForm($fieldsForm, $field, $options);
                        break;
                    default:
                        $fieldsForm->add($field->getName(), $type, $options);
                }
        }

        $form->add($fieldsForm);
        $this->forms[$field_oid] = $form;

        return $this->forms[$field_oid];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @param array $options
     */
    protected function buildBetweenComparisonForm(FormInterface $form, FieldTypeInterface $field, $options = array())
    {
        $betweenBuilder = $this->getFormFactory()->createNamedBuilder($field->getName(), 'datasource_between', null, $options);

        $fromOptions = $field->getOption('form_from_options');
        $toOptions = $field->getOption('form_to_options');
        $fromOptions = array_merge($options, $fromOptions);
        $toOptions = array_merge($options, $toOptions);
        $type = $field->getType();

        if ($field->hasOption('form_type')) {
            $type = $field->getOption('form_type');
        }

        $betweenBuilder->add('from', $type, $fromOptions);
        $betweenBuilder->add('to', $type, $toOptions);

        $form->add($betweenBuilder->getForm());
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @param array $options
     */
    protected function buildIsNullComparisonForm(FormInterface $form, FieldTypeInterface $field, $options = array())
    {
        $defaultOptions = array(
            'choices' => array(
                'null' => 'empty',
                'notnull' => 'not empty',
            ),
            'multiple' => false,
            'empty_value' => ''
        );

        if (isset($options['choices'])) {
            $options['choices'] = array_merge(
                $defaultOptions['choices'],
                array_intersect_key($options['choices'], $defaultOptions['choices'])
            );
        }

        $options = array_merge($defaultOptions, $options);
        $form->add($field->getName(), 'choice', $options);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     * @param \FSi\Component\DataSource\Field\FieldTypeInterface $field
     * @param array $options
     */
    protected function buildBooleanForm(FormInterface $form, FieldTypeInterface $field, $options = array())
    {
        $defaultOptions = array(
            'choices' => array(
                '1' => 'yes',
                '0' => 'no'
            ),
            'multiple' => false,
            'empty_value' => ''
        );

        if (isset($options['choices'])) {
            $options['choices'] = array_intersect_key($options['choices'], $defaultOptions['choices']);
        }

        $options = array_merge($defaultOptions, $options);
        $form->add($field->getName(), 'choice', $options);
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    protected function getFormFactory()
    {
        return $this->formFactory;
    }
}
