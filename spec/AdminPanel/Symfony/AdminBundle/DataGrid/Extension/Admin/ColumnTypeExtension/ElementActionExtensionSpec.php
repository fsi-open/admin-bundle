<?php


namespace spec\AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use PhpSpec\ObjectBehavior;

class ElementActionExtensionSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager $manager
     * @param \AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\ColumnType\Action $column
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $actionOptionsResolver
     */
    function let($manager, $column, $actionOptionsResolver)
    {
        $column->getActionOptionsResolver()->willReturn($actionOptionsResolver);
        $this->beConstructedWith($manager);
    }

    function it_is_datagrid_column_extension()
    {
        $this->shouldBeAnInstanceOf('FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
    }

    function it_extends_action_column_type()
    {
        $this->getExtendedColumnTypes()->shouldReturn(array('action'));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\ColumnType\Action $column
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $actionOptionsResolver
     */
    function it_adds_element_id_action_option($column, $actionOptionsResolver)
    {
        $actionOptionsResolver->setDefined(array('element'))->shouldBeCalled();
        $actionOptionsResolver->setAllowedTypes('element', 'string')->shouldBeCalled();

        $this->initOptions($column);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Symfony\ColumnType\Action $column
     * @throws \FSi\Component\DataGrid\Exception\UnknownOptionException
     */
    function it_initializes_action_route_and_parameters_based_on_element_id($manager, $element, $column)
    {
        $actionOptions = array(
            'element' => 'some_element_id',
            'additional_parameters' => array(
                'additional_action_parameter' => 'action_parameter_value'
            ),
            'other_action_option' => 'other_option_value'
        );
        $otherAction = array('any_action_option' => 'any_option_value');
        $column->getOption('actions')->willReturn(array(
            'some_action' => $actionOptions,
            'other_action' => $otherAction
        ));
        $manager->hasElement('some_element_id')->willReturn(true);
        $manager->getElement('some_element_id')->willReturn($element);
        $element->getId()->willReturn('some_element_id');
        $element->getRoute()->willReturn('admin_element_route');
        $element->getRouteParameters()->willReturn(array('additional_element_parameter' => 'element_parameter_value'));

        $column->setOption(
            'actions',
            array(
                'some_action' => array(
                    'route_name' => 'admin_element_route',
                    'additional_parameters' => array(
                        'element' => 'some_element_id',
                        'additional_element_parameter' => 'element_parameter_value',
                        'additional_action_parameter' => 'action_parameter_value'
                    ),
                    'parameters_field_mapping' => array('id' => 'id'),
                    'other_action_option' => 'other_option_value'
                ),
                'other_action' => array('any_action_option' => 'any_option_value')
            )
        )->shouldBeCalled();

        $this->filterValue($column, 'whatever')->shouldReturn('whatever');
    }
}
