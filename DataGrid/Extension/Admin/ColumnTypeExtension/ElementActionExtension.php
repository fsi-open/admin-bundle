<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;

class ElementActionExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var ManagerInterface
     */
    private $manager;

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function getExtendedColumnTypes(): array
    {
        return ['action'];
    }

    public function initOptions(ColumnTypeInterface $column): void
    {
        $column = $this->validateColumn($column);

        $column->getActionOptionsResolver()->setDefined(['element']);
        $column->getActionOptionsResolver()->setAllowedTypes('element', 'string');
    }

    public function filterValue(ColumnTypeInterface $column, $value)
    {
        $this->validateColumn($column);

        $actions = $column->getOption('actions');
        $generatedActions = [];
        foreach ($actions as $action => $actionOptions) {
            if (!$this->validateActionOptions($column, $action, $actionOptions)) {
                continue;
            }

            $generatedActions[$action] = $this->generateActionOptions($actionOptions);
            unset($actions[$action]['element']);
        }

        $column->setOption('actions', array_replace_recursive($actions, $generatedActions));

        return parent::filterValue($column, $value);
    }

    private function validateColumn(ColumnTypeInterface $column): Action
    {
        if (!($column instanceof Action)) {
            throw new RuntimeException(sprintf(
                '%s can extend only FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action, but got %s',
                get_class($this),
                get_class($column)
            ));
        }

        return $column;
    }

    private function validateActionOptions(ColumnTypeInterface $column, $action, array $actionOptions): bool
    {
        if (!isset($actionOptions['element'])) {
            return false;
        }

        if (!$this->manager->hasElement($actionOptions['element'])) {
            throw new RuntimeException(sprintf(
                'Unknown element "%s" specified in action "%s" of datagrid "%s"',
                $actionOptions['element'],
                $action,
                $column->getDataGrid()->getName()
            ));
        }

        return true;
    }

    private function generateActionOptions(array $actionOptions): array
    {
        $element = $this->manager->getElement($actionOptions['element']);

        $additionalParameters = array_merge(
            ['element' => $element->getId()],
            $element->getRouteParameters(),
            $actionOptions['additional_parameters'] ?? []
        );

        return [
            'route_name' => $element->getRoute(),
            'additional_parameters' => $additionalParameters,
            'parameters_field_mapping' => ['id' => 'id']
        ];
    }
}
