<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Bundle\DoctrineExtensionsBundle\DataGrid\ColumnType\FSiFile;
use FSi\Bundle\DoctrineExtensionsBundle\DataGrid\ColumnType\FSiImage;
use FSi\Component\DataGrid\Column\CellViewInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Boolean;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Collection;
use FSi\Component\DataGrid\Extension\Core\ColumnType\DateTime;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Money;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Number;
use FSi\Component\DataGrid\Extension\Core\ColumnType\Text;
use FSi\Component\DataGrid\Extension\Doctrine\ColumnType\Entity;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributesExtension extends ColumnAbstractTypeExtension
{
    public function getExtendedColumnTypes(): array
    {
        return [
            Text::class,
            Boolean::class,
            DateTime::class,
            Money::class,
            Number::class,
            Entity::class,
            Collection::class,
            Action::class,
            FSiFile::class,
            FSiImage::class,
        ];
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefined(['header_attr', 'cell_attr', 'container_attr', 'value_attr']);
        $optionsResolver->setAllowedTypes('header_attr', 'array');
        $optionsResolver->setAllowedTypes('cell_attr', 'array');
        $optionsResolver->setAllowedTypes('container_attr', 'array');
        $optionsResolver->setAllowedTypes('value_attr', 'array');
        $optionsResolver->setDefaults([
            'header_attr' => [],
            'cell_attr' => [],
            'container_attr' => [],
            'value_attr' => []
        ]);
    }

    public function buildCellView(ColumnInterface $column, CellViewInterface $view): void
    {
        $view->setAttribute('cell_attr', $column->getOption('cell_attr'));
        $view->setAttribute('container_attr', $column->getOption('container_attr'));
        $view->setAttribute('value_attr', $column->getOption('value_attr'));
    }

    public function buildHeaderView(ColumnInterface $column, HeaderViewInterface $view): void
    {
        $view->setAttribute('header_attr', $column->getOption('header_attr'));
    }
}
