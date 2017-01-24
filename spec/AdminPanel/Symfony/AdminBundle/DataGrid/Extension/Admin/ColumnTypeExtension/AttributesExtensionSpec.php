<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AttributesExtensionSpec extends ObjectBehavior
{
    public function it_is_column_extension()
    {
        $this->shouldBeAnInstanceOf('FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     */
    public function it_adds_actions_options($column, $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefined(['header_attr', 'cell_attr', 'container_attr', 'value_attr'])
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('header_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('cell_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('container_attr', 'array')->shouldBeCalled();
        $optionsResolver->setAllowedTypes('value_attr', 'array')->shouldBeCalled();
        $optionsResolver->setDefaults([
            'header_attr' => [],
            'cell_attr' => [],
            'container_attr' => [],
            'value_attr' => []
        ])->shouldBeCalled();

        $this->initOptions($column);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\CellViewInterface $view
     */
    public function it_passes_attributes_to_cell_view($column, $view)
    {
        $column->getOption('cell_attr')->willReturn(['cell attributes']);
        $view->setAttribute('cell_attr', ['cell attributes'])->shouldBeCalled();
        $column->getOption('container_attr')->willReturn(['container attributes']);
        $view->setAttribute('container_attr', ['container attributes'])->shouldBeCalled();
        $column->getOption('value_attr')->willReturn(['value attributes']);
        $view->setAttribute('value_attr', ['value attributes'])->shouldBeCalled();

        $this->buildCellView($column, $view);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    public function it_passes_attributes_to_header_view($column, $view)
    {
        $column->getOption('header_attr')->willReturn(['header attributes']);
        $view->setAttribute('header_attr', ['header attributes'])->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
