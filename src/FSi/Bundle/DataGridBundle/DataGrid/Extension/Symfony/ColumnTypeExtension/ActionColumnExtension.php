<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnTypeExtension;

use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\Routing\RouterInterface;

class ActionColumnExtension extends ColumnAbstractTypeExtension
{
    /**
     * Symfony Router to generate urls.
     *
     * @var \Symfony\Component\Routing\Router;
     */
    protected $router;

    /**
     * Default values for action options if not passed in column configuration.
     *
     * @var array
     */
    protected $actionOptionsDefault = array(
        'absolute' => false,
    );

    /**
     * Available action options.
     *
     * @var array
     */
    protected $actionOptionsAvailable = array(
        'parameters',
        'parameters_values',
        'anchor',
        'route_name',
        'absolute',
    );

    /**
     * Options required in action.
     *
     * @var array
     */
    protected $actionOptionsRequired = array(
        'anchor',
        'route_name',
    );

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function filterValue(ColumnTypeInterface $column, $value)
    {
        $this->validateOptions($column);

        $return = array();
        $actions = $column->getOption('actions');

        foreach ($actions as $name => $options) {
            $return[$name] = array(
                'name' => $name,
                'anchor' => $options['anchor'],
            );

            $parameters = array();
            if (isset($options['parameters'])) {
                foreach ($options['parameters'] as $mappingField => $parameterName) {
                    $parameters[$parameterName] = $value[$mappingField];
                }
            }

            if (isset($options['parameters_values'])) {
                foreach ($options['parameters_values'] as $parameterValueName => $parameterValue) {
                    $parameters[$parameterValueName] = $parameterValue;
                }
            }

            $url = $this->router->generate($options['route_name'], $parameters, $options['absolute']);

            $return[$name]['url'] = $url;
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedColumnTypes()
    {
        return array('action');
    }

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions(ColumnTypeInterface $column)
    {
        return array('actions');
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableOptions(ColumnTypeInterface $column)
    {
        return array('actions');
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @throws \InvalidArgumentException
     */
    private function validateOptions(ColumnTypeInterface $column)
    {
        $actions = $column->getOption('actions');
        if (!is_array($actions)) {
            throw new \InvalidArgumentException('Option "actions" must be an array.');
        }

        if (!count($actions)) {
            throw new \InvalidArgumentException('Option actions can\'t be empty.');
        }

        foreach ($actions as $actionName => &$options) {
            if (!is_array($options)) {
                throw new \InvalidArgumentException(sprintf('Options for action "%s" must be an array.', $actionName));
            }

            foreach ($options as $optionName => $value) {
                if (!in_array($optionName, $this->actionOptionsAvailable)) {
                    throw new \InvalidArgumentException(sprintf('Unknown option "%s" in action "%s".', $optionName, $actionName));
                }
            }

            foreach ($this->actionOptionsRequired as $optionName) {
                if (!array_key_exists($optionName, $options)) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "%s".', $actionName, $optionName));
                }
            }

            foreach ($this->actionOptionsDefault as $optionName => $value) {
                if (!array_key_exists($optionName, $options)) {
                    $options[$optionName] = $value;
                }
            }

            if (isset($options['parameters_values'])) {
                if (!is_array($options['parameters_values'])) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "parameters_values" as array.', $actionName, $optionName));
                }
            }

            if (isset($options['parameters'])) {
                if (!is_array($options['parameters'])) {
                    throw new \InvalidArgumentException(sprintf('Action "%s" require option "parameters" as array.', $actionName, $optionName));
                }

                $mappingFields = $column->getOption('field_mapping');

                foreach ($options['parameters'] as $mappingField => $routerParameter) {
                    if (!in_array($mappingField, $mappingFields, true)) {
                        throw new \InvalidArgumentException(sprintf('Unknown mapping_field "%s". Maybe you should consider using option "parameters_values"?', $mappingField));
                    }
                }
            }
        }

        $column->setOption('actions', $actions);
    }
}
