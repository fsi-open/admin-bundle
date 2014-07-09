<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;

class EditActionExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\Manager
     */
    private $manager;

    /**
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @return array
     */
    public function getExtendedColumnTypes()
    {
        return array('action');
    }

    /**
     * @param ColumnTypeInterface $column
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array(
            'admin_edit_element_id' => null,
            'admin_display_element_id' => null
        ));

        $column->getOptionsResolver()->setAllowedTypes(array(
            'admin_edit_element_id' => array('string', 'null'),
            'admin_display_element_id' => array('string', 'null'),
        ));
    }

    /**
     * @param ColumnTypeInterface $column
     * @param mixed $value
     * @return mixed
     */
    public function filterValue(ColumnTypeInterface $column, $value)
    {
        if ($this->editElementIdIsValid($column)) {
            $actions = array_merge(
                array(
                    'edit' => array(
                        'route_name' => 'fsi_admin_form',
                        'additional_parameters' =>  array('element' =>  $column->getOption('admin_edit_element_id')),
                        'parameters_field_mapping' => array('id' => 'id')
                    )
                ),
                $column->getOption('actions')
            );

            $column->setOption('actions', $actions);
        }

        if ($this->editDisplayIdIsValid($column)) {
            $actions = array_merge(
                array(
                    'display' => array(
                        'route_name' => 'fsi_admin_display',
                        'additional_parameters' =>  array('element' =>  $column->getOption('admin_display_element_id')),
                        'parameters_field_mapping' => array('id' => 'id')
                    )
                ),
                $column->getOption('actions')
            );

            $column->setOption('actions', $actions);
        }

        return parent::filterValue($column, $value);
    }

    /**
     * @param ColumnTypeInterface $column
     * @return bool
     */
    private function editElementIdIsValid(ColumnTypeInterface $column)
    {
        return $column->hasOption('admin_edit_element_id') && $this->manager->hasElement($column->getOption('admin_edit_element_id'));
    }

    /**
     * @param ColumnTypeInterface $column
     * @return bool
     */
    private function editDisplayIdIsValid(ColumnTypeInterface $column)
    {
        return $column->hasOption('admin_display_element_id') && $this->manager->hasElement($column->getOption('admin_display_element_id'));
    }
}
