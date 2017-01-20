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

class DateTime extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $format = $this->getOption('datetime_format');
        $inputValues = $this->getInputData($value);

        $return = array();
        foreach ($inputValues as $field => $value) {
            if (empty($value)) {
                $return[$field]  = null;
                continue;
            }

            if (is_string($format)) {
                $return[$field] = $value->format($format);
                continue;
            }
            if (is_array($format)) {
                if (!array_key_exists($field, $format)) {
                    throw new DataGridColumnException(
                        sprintf('There is not format for field "%s" in "format" option value.', $field)
                    );
                }
                $return[$field] = $value->format($format[$field]);
            }
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'datetime_format' => 'Y-m-d H:i:s',
            'input_type' => null,
            'input_field_format' => null
        ));

        $this->getOptionsResolver()->setAllowedTypes('input_field_format', array('null', 'array', 'string'));

        $this->getOptionsResolver()->setAllowedValues('input_type', array(
            null,
            'string',
            'timestamp',
            'datetime',
            'array'
        ));
    }

    private function getInputData($value)
    {
        $input = $this->getOption('input_type');
        $mappingFormat = $this->getOption('input_field_format');

        if (!isset($input)) {
            $input = $this->guessInput($value);
        }

        $mappingFields = $this->getOption('field_mapping');
        $inputData = array();
        foreach ($mappingFields as $field) {
            $inputData[$field] = null;
        }

        switch (strtolower($input)) {
            case 'array':
                if (!isset($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option is missing. Example: "mapping_fields_format" => array("mapping_field_name" => array("input" => "datetime"))'
                    );
                }
                if (!is_array($mappingFormat)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option value must be an array with keys that match mapping fields names.'
                    );
                }
                if (count($mappingFormat) != count($value)) {
                    throw new DataGridColumnException(
                        '"mapping_fields_format" option value array must have same count as "mapping_fields" option value array.  '
                    );
                }

                foreach ($mappingFormat as $field => $input) {
                    if (!array_key_exists($field, $value)) {
                        throw new DataGridColumnException(
                            sprintf('Unknown mapping_field "%s".', $field)
                        );
                    }
                    if (!is_array($input)) {
                        throw new DataGridColumnException(
                            sprintf('"%s" should be an array.', $field)
                        );
                    }
                    $fieldInput = (array_key_exists('input_type', $input)) ? $input['input_type'] : $this->guessInput($value[$field]);

                    switch (strtolower($fieldInput)) {
                        case 'string':
                            $mappingFormat = (array_key_exists('datetime_format', $input)) ? $input['datetime_format'] : null;
                            if (!isset($mappingFormat)) {
                                throw new DataGridColumnException(
                                    sprintf('"datetime_format" option is required in "mapping_fields_datetime_format" for field "%s".', $field)
                                );
                            }
                            if (empty($value[$field])) {
                                $inputData[$field] = null;
                            } else {
                                $inputData[$field] = $this->transformStringToDateTime($value[$field], $mappingFormat);
                            }

                            break;
                        case 'timestamp':
                            if (empty($value[$field])) {
                                $inputData[$field] = null;
                            } else {
                                $inputData[$field] = $this->transformTimestampToDateTime($value[$field]);
                            }
                            break;
                        case 'datetime':
                            if (!empty($value[$field]) && !($value[$field] instanceof \DateTime)) {
                                throw new DataGridColumnException(
                                    sprintf('Value in field "%s" is "%s" type instead of "\DateTime" instance.', $field, gettype($value[$field]))
                                );
                            }

                            $inputData[$field] = $value[$field];
                            break;
                        default:
                            throw new DataGridColumnException(
                                sprintf('"%s" is not valid input option value for field "%s". '.
                                'You should consider using one of "array", "string", "datetime" or "timestamp" input option values. ', $fieldInput, $field)
                            );
                    }
                }
                break;

            case 'string':
                $field = key($value);
                $value = current($value);

                if (!empty($value) && !is_string($value)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not a valid string.', $field)
                    );
                }

                    if (empty($value)) {
                        $inputData[$field] = null;
                    } else {
                        $inputData[$field] = $this->transformStringToDateTime($value, $mappingFormat);
                    }
                break;

            case 'datetime':
                $field = key($value);
                $value = current($value);

                if (!empty($value) && !($value instanceof \DateTime)) {
                    throw new DataGridColumnException(
                        sprintf('Value in field "%s" is not instance of "\DateTime"', $field)
                    );
                }

                $inputData[$field] = $value;
                break;

            case 'timestamp':
                $field = key($value);
                $value = current($value);

                if (empty($value)) {
                    $inputData[$field] = null;
                } else {
                    $inputData[$field] = $this->transformTimestampToDateTime($value);
                }
                break;

            default:
                throw new DataGridColumnException(
                    sprintf('"%s" is not valid input option value. '.
                    'You should consider using one of "array", "string", "datetime" or "timestamp" input option values. ', $input)
                );
        }

        return $inputData;
    }

    /**
     * If input option value is not passed into column this method should
     * be called to guess input type from column $value.
     *
     * @param array $value
     */
    private function guessInput($value)
    {
        if (is_array($value)) {
            if (count($value) > 1) {
                throw new DataGridColumnException(
                'If you want to use more that one mapping fields you need to set "input" option value "array".'
                );
            }
            $value = current($value);
        }

        if ($value instanceof \DateTime) {
            return 'datetime';
        }

        if (is_numeric($value)) {
            return 'timestamp';
        }

        if (is_string($value) || empty($value)) {
            return 'string';
        }

        return null;
    }

    /**
     * @param string $value
     * @param string $mappingFormat
     * @return \DateTime
     */
    private function transformStringToDateTime($value, $mappingFormat)
    {
        if (!isset($mappingFormat)) {
            throw new DataGridColumnException(
                '"mapping_fields_format" option is missing. Example: "mapping_fields_format" => "Y-m-d H:i:s"'
            );
        }

        if (!is_string($mappingFormat)) {
            throw new DataGridColumnException(
                'When using input type "string", "mapping_fields_format" option must be an string that contains valid data format'
            );
        }

        $dateTime = \DateTime::CreateFromFormat($mappingFormat, $value);

        if (!$dateTime instanceof \DateTime) {
            throw new DataGridColumnException(
                sprintf('value "%s" does not fit into format "%s" ', $value, $mappingFormat)
            );
        }

        return $dateTime;
    }

    /**
     * @param int
     * @return \DateTime
     */
    private function transformTimestampToDateTime($value)
    {
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException(
                sprintf('Value in column "%s" should be timestamp but "%s" type was detected. Maybe you should consider using different "input" opition value?', $this->getName(), gettype($value))
            );
        }

        $dateTime = new \DateTime();
        $dateTime->setTimestamp($value);

        if (!$dateTime instanceof \DateTime) {
            throw new DataGridColumnException(
                sprintf('value "%s" is not a valid timestamp', $value)
            );
        }

        return $dateTime;
    }
}
