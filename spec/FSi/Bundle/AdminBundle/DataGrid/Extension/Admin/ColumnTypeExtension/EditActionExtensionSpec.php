<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EditActionExtensionSpec extends ObjectBehavior
{
    function let(Manager $manager, ColumnTypeInterface $columnType)
    {
        $columnType->hasOption('admin_display_element_id')->willReturn(false);
        $columnType->hasOption('admin_edit_element_id')->willReturn(false);

        $manager->hasElement('news')->willReturn(true);
        $manager->hasElement('category')->willReturn(false);
        $this->beConstructedWith($manager);
    }

    function it_add_edit_action_when_edit_element_id_option_is_set(ColumnTypeInterface $columnType)
    {
        $columnType->hasOption('admin_edit_element_id')->willReturn(true);
        $columnType->getOption('admin_edit_element_id')->willReturn('news');
        $columnType->getOption('actions')->willReturn(array(
            'create' => array(
                'route_name' => 'create'
            )
        ));

        $columnType->setOption('actions', array(
            'edit' => array(
                'route_name' => 'fsi_admin_form',
                'additional_parameters' =>  array('element' =>  'news'),
                'parameters_field_mapping' => array('id' => 'id')
            ),
            'create' => array(
                'route_name' => 'create'
            )
        ))->shouldBeCalled();

        $this->filterValue($columnType, array());
    }

    function it_add_display_action_when_displat_element_id_option_is_set(ColumnTypeInterface $columnType)
    {
        $columnType->hasOption('admin_display_element_id')->willReturn(true);
        $columnType->getOption('admin_display_element_id')->willReturn('news');
        $columnType->getOption('actions')->willReturn(array(
            'create' => array(
                'route_name' => 'create'
            )
        ));

        $columnType->setOption('actions', array(
            'display' => array(
                'route_name' => 'fsi_admin_display',
                'additional_parameters' =>  array('element' =>  'news'),
                'parameters_field_mapping' => array('id' => 'id')
            ),
            'create' => array(
                'route_name' => 'create'
            )
        ))->shouldBeCalled();

        $this->filterValue($columnType, array());
    }

    function it_ignore_elements_that_are_not_registered_in_manager(ColumnTypeInterface $columnType)
    {
        $columnType->hasOption('admin_edit_element_id')->willReturn(true);
        $columnType->getOption('admin_edit_element_id')->willReturn('category');
        $columnType->setOption('actions', Argument::any())->shouldNotBeCalled();

        $this->filterValue($columnType, array());
    }
}
