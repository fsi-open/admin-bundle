<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Component\DataGrid\Extension\Core\ColumnType;

use FSi\Component\DataGrid\Column\ColumnAbstractType;

class Text extends ColumnAbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue($value)
    {
        $trim = (boolean) $this->getOption('trim');
        if (isset($trim) && $trim == true) {
            foreach ($value as &$val) {
                if (empty($val)) {
                    continue;
                }

                $val = trim($val);
            }
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function initOptions()
    {
        $this->getOptionsResolver()->setDefaults(array(
            'trim' => false
        ));

        $this->getOptionsResolver()->setAllowedTypes('trim', 'bool');
    }
}
