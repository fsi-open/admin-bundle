<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\DependentElement;
use FSi\Bundle\AdminBundle\Admin\Display\Element as DisplayElement;
use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\DataGridBundle\DataGrid\Extension\Symfony\ColumnType\Action;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
        return [Action::class];
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $defaultRouteClosure = function (Options $options, $previousValue) {
            if (!isset($options['element'])) {
                return $previousValue;
            }

            return $this->getElement($options['element'])->getRoute();
        };

        $defaultParametersClosure = function (Options $options, array $previousValue): array {
            if (!isset($options['element'])) {
                return $previousValue;
            }

            $element = $this->getElement($options['element']);

            return array_merge(['element' => $element->getId()], $element->getRouteParameters(), $previousValue);
        };

        $defaultMappingClosure = function (Options $options, array $previousValue): array {
            if (!isset($options['element'])) {
                return $previousValue;
            }

            $defaultMapping = ['id' => 'id'];

            return array_merge($defaultMapping, $previousValue);
        };

        $optionsResolver->setDefault(
            'action_options_resolver',
            function (Options $options, OptionsResolver $previousValue) use ($defaultRouteClosure, $defaultParametersClosure, $defaultMappingClosure): OptionsResolver {
                $previousValue->setDefined(['element']);
                $previousValue->setAllowedTypes('element', 'string');
                $previousValue->setDefault('route_name', $defaultRouteClosure);
                $previousValue->setDefault('additional_parameters', $defaultParametersClosure);
                $previousValue->setDefault('parameters_field_mapping', $defaultMappingClosure);

                return $previousValue;
            }
        );
    }

    private function getElement(string $element): Element
    {
        if (!$this->manager->hasElement($element)) {
            throw new RuntimeException(sprintf(
                'Unknown element "%s" specified in datagrid action',
                $element
            ));
        }

        return $this->manager->getElement($element);
    }
}
