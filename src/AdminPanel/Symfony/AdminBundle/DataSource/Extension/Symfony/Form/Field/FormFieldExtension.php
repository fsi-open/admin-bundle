<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataSource\Extension\Symfony\Form\Field;

use FSi\Component\DataSource\Field\FieldTypeInterface;
use FSi\Component\DataSource\Field\FieldAbstractExtension;
use Symfony\Component\Translation\TranslatorInterface;

class FormFieldExtension extends FieldAbstractExtension
{
    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    protected $translator;

    /**
     * @param \Symfony\Component\Translation\TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedFieldTypes()
    {
        return ['text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean'];
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
        if ($field->getComparison() == 'isNull') {
            $field->getOptionsResolver()
                ->setDefaults([
                    'form_options' => [
                        'choices' => [
                            'null' => $this->translator->trans('datasource.form.choices.is_null', [], 'DataSourceBundle'),
                            'no_null' => $this->translator->trans('datasource.form.choices.is_not_null', [], 'DataSourceBundle')
                        ]
                    ]
                ]);
        } elseif ($field->getType() == 'boolean') {
            $field->getOptionsResolver()
                ->setDefaults([
                    'form_options' => [
                        'choices' => [
                            '1' => $this->translator->trans('datasource.form.choices.yes', [], 'DataSourceBundle'),
                            '0' => $this->translator->trans('datasource.form.choices.no', [], 'DataSourceBundle')
                        ]
                    ]
                ]);
        }
    }
}
