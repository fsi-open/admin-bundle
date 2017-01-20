<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RowType extends AbstractType
{
    /**
     * @var array
     */
    protected $fields;

    /**
     * @param array $fields
     */
    public function __construct($fields = array())
    {
        $this->fields = $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        foreach ($this->fields as $field) {
            $builder->add($field['name'], $field['type'], $field['options']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'row';
    }
}
