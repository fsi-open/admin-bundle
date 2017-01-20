<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;
use FSi\Component\DataGrid\Exception\DataGridColumnException;

class Money extends ColumnAbstractType
{
    const ROUND_HALF_UP = PHP_ROUND_HALF_UP;
    const ROUND_HALF_DOWN = PHP_ROUND_HALF_DOWN;
    const ROUND_HALF_EVEN = PHP_ROUND_HALF_EVEN;
    const ROUND_HALF_ODD = PHP_ROUND_HALF_ODD;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'money';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $precision = $this->getOption('precision');
        $roundmode = $this->getOption('round_mode');
        $decimals = $this->getOption('decimals');
        $decPoint = $this->getOption('dec_point');
        $thousands = $this->getOption('thousands_sep');
        $currencyField = $this->getOption('currency_field');
        $currencyValue = $this->getOption('currency');
        $mappingFields = $this->getOption('field_mapping');
        $currencySeparator = $this->getOption('value_currency_separator');

        if (!isset($currencyField) && !isset($currencyValue)) {
            throw new DataGridColumnException(
                sprintf('At least one option from "currency" and "currency_field" must be defined in "%s" field.', $this->getName())
            );
        }

        $currency = $currencyValue;
        if (isset($currencyField)) {
            if (!in_array($currencyField, $mappingFields)) {
                throw new DataGridColumnException(
                    sprintf('There is no field with name "%s".', $currencyField)
                );
            }

            $currency = $value[$currencyField];
            unset($value[$currencyField]);
        }

        foreach ($value as $fieldName => &$val) {
            if (empty($val)) {
                continue;
            }

            $val = round($val, $precision, $roundmode);
            $val = number_format($val, $decimals, $decPoint, $thousands);

            $val = $val . $currencySeparator . $currency;
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'round_mode' => self::ROUND_HALF_UP,
            'precision' => 2,
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ',',
            'value_currency_separator' => ' ',
            'currency' => null,
            'currency_field' => null,
        ));

        $this->getOptionsResolver()->setAllowedTypes('round_mode', 'integer');
        $this->getOptionsResolver()->setAllowedTypes('precision', 'integer');
        $this->getOptionsResolver()->setAllowedTypes('decimals', 'integer');
        $this->getOptionsResolver()->setAllowedTypes('decimals', 'integer');
        $this->getOptionsResolver()->setAllowedTypes('dec_point', 'string');
        $this->getOptionsResolver()->setAllowedTypes('thousands_sep', 'string');
        $this->getOptionsResolver()->setAllowedTypes('value_currency_separator', 'string');
        $this->getOptionsResolver()->setAllowedTypes('currency', array('null', 'string'));
        $this->getOptionsResolver()->setAllowedTypes('currency_field', array('null', 'string'));
    }
}
