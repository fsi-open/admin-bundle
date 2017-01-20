<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;

class ValueFormatColumnOptionsExtension extends ColumnAbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildCellView(ColumnTypeInterface $column, CellViewInterface $view)
    {
        $this->validateEmptyValueOption($column);
        $value = $this->populateValue($view->getValue(), $column->getOption('empty_value'));
        $glue = $column->getOption('value_glue');
        $format = $column->getOption('value_format');

        $value = $this->formatValue($value, $format, $glue);

        if (!isset($glue, $format) && is_array($value)) {
            throw new \InvalidArgumentException(sprintf('At least one of "format" or "glue" option is missing in column: "%s".', $column->getName()));
        }

        $view->setValue($value);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedColumnTypes()
    {
        return array(
            'text',
            'boolean',
            'datetime',
            'collection',
            'number',
            'money',
            'gedmo_tree',
        );
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'value_glue' => null,
            'value_format' => null,
            'empty_value' => '',
        ));

        $column->getOptionsResolver()->setAllowedTypes('value_glue', array('string', 'null'));
        $column->getOptionsResolver()->setAllowedTypes('value_format', array('string', 'Closure', 'null'));
        $column->getOptionsResolver()->setAllowedTypes('empty_value', 'string');
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @throws \InvalidArgumentException
     */
    private function validateEmptyValueOption(ColumnTypeInterface $column)
    {
        $emptyValue = $column->getOption('empty_value');
        $mappingFields = $column->getOption('field_mapping');

        if (is_string($emptyValue)) {
            return;
        }

        if (!is_array($emptyValue)) {
            throw new \InvalidArgumentException(
                sprintf('Option "empty_value" in column: "%s" must be a array.', $column->getName())
            );
        }

        foreach ($emptyValue as $field => $value) {
            if (!in_array($field, $mappingFields)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Mapping field "%s" does\'t exists in column: "%s".',
                        $field,
                        $column->getName()
                    )
                );
            }

            if (!is_string($value)) {
                throw new \InvalidArgumentException(
                    sprintf(
                        'Option "empty_value" for field "%s" in column: "%s" must be a valid string.',
                        $field,
                        $column->getName()
                    )
                );
            }
        }
    }

    /**
     * @param mixed $value
     * @param mixed $emptyValue
     * @return array|string
     */
    private function populateValue($value, $emptyValue)
    {
        if (is_string($emptyValue)) {
            if (!isset($value) || (is_string($value) && !strlen($value))) {
                return $emptyValue;
            }

            if (is_array($value)) {
                foreach ($value as &$val) {
                    if (!isset($val) || (is_string($val) && !strlen($val))) {
                        $val = $emptyValue;
                    }
                }
            }

            return $value;
        }

        /**
         * If value is simple string and $empty_value is array there is no way
         * to guess which empty_value should be used.
         */
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value)) {
            foreach ($value as $field => &$fieldValue)  {
                if (empty($fieldValue)) {
                    $fieldValue = array_key_exists($field, $emptyValue)
                        ? $emptyValue[$field]
                        : '';
                }
            }
        }

        return $value;
    }

    /**
     * @param $value
     * @param null $format
     * @param null $glue
     * @return array|mixed|string
     */
    private function formatValue($value, $format = null, $glue = null)
    {
        if (is_array($value) && isset($glue) && !isset($format)) {
            $value = implode($glue, $value);
        }

        if (isset($format)) {
            if (is_array($value)) {
                if (isset($glue)) {
                    $renderedValues = array();
                    foreach ($value as $val) {
                        $renderedValues[] = $this->formatSingleValue($val, $format);
                    }

                    $value = implode($glue, $renderedValues);
                } else {
                    $value = $this->formatMultipleValues($value, $format);
                }
            } else {
                $value = $this->formatSingleValue($value, $format);
            }
        }

        if (is_array($value) && count($value) == 1) {
            reset($value);
            $value = current($value);
        }

        return $value;
    }

    /**
     * @param string $value
     * @param string $template
     * @return string
     */
    private function formatSingleValue($value, $template)
    {
        if ($template instanceof \Closure) {
            return $template($value);
        }

        return sprintf($template, $value);
    }

    /**
     * @param $value
     * @param $template
     * @return string
     */
    private function formatMultipleValues($value, $template)
    {
        if ($template instanceof \Closure) {
            return $template($value);
        }

        return vsprintf($template, $value);
    }
}
