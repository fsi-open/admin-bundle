<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Doctrine\ColumnType;

use Doctrine\Common\Collections\Collection;
use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Entity extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($object)
    {
        $value = $this->getDataMapper()->getData($this->getOption('relation_field'), $object);

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        if ($value instanceof Collection) {
            $value = $value->toArray();
        }

        $values = array();
        $objectValues = array();
        $mappingFields = $this->getOption('field_mapping');

        if (is_array($value)) {
            foreach ($value as $object) {
                foreach ($mappingFields as $field) {
                    $objectValues[$field] = $this->getDataMapper()->getData($field, $object);
                }

                $values[] = $objectValues;
            }
        } else {
            foreach ($mappingFields as $field) {
                $objectValues[$field] = isset($value)
                    ? $this->getDataMapper()->getData($field, $value)
                    : null;
            }

            $values[] = $objectValues;
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'relation_field' => $this->getName(),
        ));

        $this->getOptionsResolver()->setAllowedTypes('relation_field', 'string');
    }
}
