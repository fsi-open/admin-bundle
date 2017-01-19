<?php

namespace AdminPanel\Symfony\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use AdminPanel\Symfony\AdminBundle\Admin\Manager;
use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;

class ElementActionExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var \AdminPanel\Symfony\AdminBundle\Admin\Manager
     */
    private $manager;

    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public function getExtendedColumnTypes()
    {
        return array('action');
    }

    /**
     * @inheritdoc
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $this->validateColumn($column);

        $column->getActionOptionsResolver()->setDefined(array('element'));
        $column->getActionOptionsResolver()->setAllowedTypes('element', 'string');
    }

    public function filterValue(ColumnTypeInterface $column, $value)
    {
        $this->validateColumn($column);

        $actions = $column->getOption('actions');
        $generatedActions = array();
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

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     */
    private function validateColumn(ColumnTypeInterface $column)
    {
        if (!($column instanceof Action)) {
            throw new RuntimeException(sprintf(
                '%s can extend only FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action, but got %s',
                get_class($this),
                get_class($column)
            ));
        }
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param string $action
     * @param array $actionOptions
     */
    private function validateActionOptions(ColumnTypeInterface $column, $action, array $actionOptions)
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

    /**
     * @param array $actionOptions
     * @return array
     */
    private function generateActionOptions(array $actionOptions)
    {
        $element = $this->manager->getElement($actionOptions['element']);

        $additionalParameters = array_merge(
            array('element' => $element->getId()),
            $element->getRouteParameters(),
            isset($actionOptions['additional_parameters']) ? $actionOptions['additional_parameters'] : array()
        );

        return array(
            'route_name' => $element->getRoute(),
            'additional_parameters' => $additionalParameters,
            'parameters_field_mapping' => array('id' => 'id')
        );
    }
}
