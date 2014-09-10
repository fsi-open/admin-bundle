<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Component\DataGrid\Extension\Symfony\ColumnType\Action;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ElementActionExtensionSpec extends ObjectBehavior
{
    function let(
        Manager $manager,
        Action $column,
        OptionsResolverInterface $actionOptionsResolver
    ) {
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

    function it_adds_element_id_action_option(
        Action $column,
        OptionsResolverInterface $actionOptionsResolver
    ) {
        $actionOptionsResolver->setOptional(array('element_id'))->shouldBeCalled();
        $actionOptionsResolver->setAllowedTypes(array('element_id' => 'string'))->shouldBeCalled();

        $this->initOptions($column);
    }

    function it_initializes_action_route_and_parameters_based_on_element_id(
        Manager $manager,
        ElementInterface $element,
        Action $column
    ) {
        $actionOptions = array(
            'element_id' => 'some_element_id',
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
