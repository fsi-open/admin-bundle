<?php

/**
 * (c) FSi Sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataSourceBundle\DataSource\Extension\Symfony\Form\Field;

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
        return array('text', 'number', 'date', 'time', 'datetime', 'entity', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(FieldTypeInterface $field)
    {
        if ($field->getComparison() == 'isNull') {
            $field->getOptionsResolver()
                ->setDefaults(array(
                    'form_options' => array(
                        'choices' => array(
                            'null' => $this->translator->trans('datasource.form.choices.is_null', array(), 'DataSourceBundle'),
                            'no_null' => $this->translator->trans('datasource.form.choices.is_not_null', array(), 'DataSourceBundle')
                        )
                    )
                ));
        } else if ($field->getType() == 'boolean') {
            $field->getOptionsResolver()
                ->setDefaults(array(
                    'form_options' => array(
                        'choices' => array(
                            '1' => $this->translator->trans('datasource.form.choices.yes', array(), 'DataSourceBundle'),
                            '0' => $this->translator->trans('datasource.form.choices.no', array(), 'DataSourceBundle')
                        )
                    )
                ));
        }
    }
}
