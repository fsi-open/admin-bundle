<?php

declare(strict_types=1);

namespace AdminPanel\Symfony\AdminBundle\DataGrid\Extension\View\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Translation\TranslatorInterface;

class BooleanColumnExtension extends ColumnAbstractTypeExtension
{
    /**
     * Symfony Translator to generate translations.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritDoc}
     */
    public function getExtendedColumnTypes()
    {
        return ['boolean'];
    }

    /**
     * {@inheritDoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults([
            'true_value' => $this->translator->trans('datagrid.boolean.yes', [], 'FSiAdminBundle'),
            'false_value' => $this->translator->trans('datagrid.boolean.no', [], 'FSiAdminBundle')
        ]);

        $translator = $this->translator;
        $column->getOptionsResolver()->setNormalizer(
            'form_options',
            function (Options $options, $value) use ($translator) {
                if ($options['editable'] && count($options['field_mapping']) == 1) {
                    $field = $options['field_mapping'][0];

                    return array_merge(
                        [
                            $field => [
                                'choices' => [
                                    0 => $translator->trans('datagrid.boolean.no', [], 'FSiAdminBundle'),
                                    1 => $translator->trans('datagrid.boolean.yes', [], 'FSiAdminBundle')
                                ]
                            ]
                        ],
                        $value
                    );
                }

                return $value;
            }
        );
        $column->getOptionsResolver()->setNormalizer(
            'form_type',
            function (Options $options, $value) {
                if ($options['editable'] && count($options['field_mapping']) == 1) {
                    $field = $options['field_mapping'][0];

                    return array_merge(
                        [$field => 'choice'],
                        $value
                    );
                }

                return $value;
            }
        );
    }
}
