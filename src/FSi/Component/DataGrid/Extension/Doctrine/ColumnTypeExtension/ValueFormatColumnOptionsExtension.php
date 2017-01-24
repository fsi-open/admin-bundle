<?php

declare(strict_types=1);

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Exception\DataGridException;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $value = [];
        $values = $view->getValue();
        if (($emptyValue = $column->getOption('empty_value')) !== null) {
            $values = $this->populateValues($values, $emptyValue);
        }
        $glue = $column->getOption('value_glue');
        $format = $column->getOption('value_format');

        foreach ($values as $val) {
            $objectValue = null;

            if (isset($glue) && !isset($format)) {
                $objectValue = implode($glue, $val);
            }

            if (isset($format)) {
                if (isset($glue)) {
                    $formatedValues = [];
                    foreach ($val as $fieldValue) {
                        $formatedValues[] = sprintf($format, $fieldValue);
                    }

                    $objectValue = implode($glue, $formatedValues);
                } else {
                    $objectValue = vsprintf($format, $val);
                }
            }

            $value[] = $objectValue;
        }

        $value = implode($column->getOption('glue_multiple'), $value);

        $view->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
     public function getExtendedColumnTypes()
     {
         return [
             'entity',
         ];
     }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults([
            'glue_multiple' => ' ',
            'value_glue' => ' ',
            'value_format' => '%s',
            'empty_value' => null
        ]);

        $column->getOptionsResolver()->setAllowedTypes('glue_multiple', ['string']);
        $column->getOptionsResolver()->setAllowedTypes('value_glue', ['string', 'null']);
        $column->getOptionsResolver()->setAllowedTypes('value_format', ['string', 'null']);
        $column->getOptionsResolver()->setAllowedTypes('empty_value', ['array', 'string', 'null']);
    }

    /**
     * @param mixed $values
     * @param mixed $emptyValue
     * @return array
     */
    private function populateValues($values, $emptyValue)
    {
        foreach ($values as &$val) {
            foreach ($val as $fieldKey => &$fieldValue) {
                if (!isset($fieldValue)) {
                    $fieldValue = $this->populateValue($fieldKey, $fieldValue, $emptyValue);
                }
            }
        }

        return $values;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param mixed $emptyValue
     * @return string
     * @throws \FSi\Component\DataGrid\Exception\DataGridException
     */
    private function populateValue($key, $value, $emptyValue)
    {
        if (is_string($emptyValue)) {
            $value = $emptyValue;
        }

        if (is_array($emptyValue)) {
            if (isset($emptyValue[$key])) {
                $value = $emptyValue[$key];
            } else {
                throw new DataGridException(
                    sprintf('Not found key "%s" in empty_value array', $key)
                );
            }
        }

        return $value;
    }
}
