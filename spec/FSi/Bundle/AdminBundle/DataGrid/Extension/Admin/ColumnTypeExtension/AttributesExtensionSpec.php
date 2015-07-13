<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttributesExtensionSpec extends ObjectBehavior
{
    function it_is_column_extension()
    {
        $this->shouldBeAnInstanceOf('FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     */
    function it_adds_actions_options($column, $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefined(array('header_attr', 'cell_attr', 'container_attr', 'value_attr'))
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('header_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('cell_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('container_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('value_attr', 'array')->shouldBeCalled();
        $optionsResolver->setDefaults(array(
            'header_attr' => array(),
            'cell_attr' => array(),
            'container_attr' => array(),
            'value_attr' => array()
        ))->shouldBeCalled();

        $this->initOptions($column);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     */
    function it_passes_attributes_to_cell_view($column, $view)
    {
        $column->getOption('cell_attr')->willReturn(array('cell attributes'));
        $view->setAttribute('cell_attr', array('cell attributes'))->shouldBeCalled();
        $column->getOption('container_attr')->willReturn(array('container attributes'));
        $view->setAttribute('container_attr', array('container attributes'))->shouldBeCalled();
        $column->getOption('value_attr')->willReturn(array('value attributes'));
        $view->setAttribute('value_attr', array('value attributes'))->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_passes_attributes_to_header_view($column, $view)
    {
        $column->getOption('header_attr')->willReturn(array('header attributes'));
        $view->setAttribute('header_attr', array('header attributes'))->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
