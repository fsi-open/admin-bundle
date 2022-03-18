<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use Closure;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\DataGridBundle\DataGrid\ColumnType\Action;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function array_merge;

class ElementActionExtension extends ColumnAbstractTypeExtension
{
    private ManagerInterface $manager;

    public static function getExtendedColumnTypes(): array
    {
        return [Action::class];
    }

    public function __construct(ManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefault('actions', function (OptionsResolver $actionOptionsResolver): void {
            $actionOptionsResolver->setPrototype(true);
            $actionOptionsResolver->setDefaults([
                'redirect_uri' => true,
                'absolute' => UrlGeneratorInterface::ABSOLUTE_PATH,
                'url_attr' => [],
                'content' => null,
                'parameters_field_mapping' => [],
                'additional_parameters' => [],
                'element' => null,
            ]);

            $actionOptionsResolver->setAllowedTypes('url_attr', ['array', Closure::class]);
            $actionOptionsResolver->setAllowedTypes('content', ['null', 'string', Closure::class]);
            $actionOptionsResolver->setAllowedTypes('element', ['null', 'string']);
            $actionOptionsResolver->setAllowedTypes('parameters_field_mapping', ['null', 'array']);
            $actionOptionsResolver->setAllowedTypes('additional_parameters', ['null', 'array']);

            $actionOptionsResolver->setRequired([
                'route_name',
            ]);
            $actionOptionsResolver->setAllowedTypes('route_name', 'string');

            $actionOptionsResolver->setDefault(
                'route_name',
                function (Options $options, ?string $previousValue): ?string {
                    if (null === ($options['element'] ?? null)) {
                        return $previousValue;
                    }

                    return $this->manager->getElement($options['element'])->getRoute();
                }
            );
            $actionOptionsResolver->setDefault(
                'additional_parameters',
                function (Options $options, ?array $previousValue): ?array {
                    if (null === ($options['element'] ?? null)) {
                        return $previousValue;
                    }

                    $element = $this->manager->getElement($options['element']);

                    return array_merge(
                        ['element' => $element->getId()],
                        $element->getRouteParameters(),
                        $previousValue ?? []
                    );
                }
            );
            $actionOptionsResolver->setDefault(
                'parameters_field_mapping',
                function (Options $options, ?array $previousValue): ?array {
                    if (null === ($options['element'] ?? null)) {
                        return $previousValue;
                    }

                    return ['id' => 'id'];
                }
            );
        });
    }
}
