<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\ColumnType\Batch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class BatchActionExtension extends ColumnAbstractTypeExtension
{
    protected ManagerInterface $manager;

    protected RouterInterface $router;

    protected RequestStack $requestStack;

    /**
     * @var FormBuilderInterface<string,FormBuilderInterface>
     */
    protected FormBuilderInterface $formBuilder;

    protected OptionsResolver $actionOptionsResolver;

    public static function getExtendedColumnTypes(): array
    {
        return [Batch::class];
    }

    /**
     * @param ManagerInterface $manager
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     * @param FormBuilderInterface<string,FormBuilderInterface> $formBuilder
     */
    public function __construct(
        ManagerInterface $manager,
        RequestStack $requestStack,
        RouterInterface $router,
        FormBuilderInterface $formBuilder
    ) {
        $this->manager = $manager;
        $this->requestStack = $requestStack;
        $this->router = $router;
        $this->formBuilder = $formBuilder;
    }

    public function initOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([
            'translation_domain' => 'FSiAdminBundle'
        ]);
        $optionsResolver->setAllowedTypes('translation_domain', ['string']);
        $optionsResolver->setDefault('actions', function (OptionsResolver $actionOptionsResolver): void {
            $actionOptionsResolver->setPrototype(true);
            $actionOptionsResolver->setRequired([
                'route_name'
            ]);
            $actionOptionsResolver->setDefined([
                'element'
            ]);
            $actionOptionsResolver->setDefaults([
                'route_name' => function (Options $options) {
                    return $this->getDefaultRouteName($options);
                },
                'additional_parameters' => [],
                'label' => null,
                'redirect_uri' => true,
            ]);
            $actionOptionsResolver->setNormalizer(
                'additional_parameters',
                function (Options $options, $value): array {
                    return $this->normalizeAdditionalParameters($options, $value);
                }
            );
            $actionOptionsResolver->setAllowedTypes('element', 'string');
            $actionOptionsResolver->setAllowedTypes('route_name', 'string');
            $actionOptionsResolver->setAllowedTypes('additional_parameters', 'array');
            $actionOptionsResolver->setAllowedTypes('label', ['string', 'null']);
            $actionOptionsResolver->setAllowedTypes('redirect_uri', ['string', 'bool']);
        });
        $optionsResolver->setAllowedTypes('actions', ['array']);
    }

    public function buildHeaderView(ColumnInterface $column, HeaderViewInterface $view): void
    {
        $this->buildBatchForm(
            $column,
            $this->buildBatchActions($column)
        );

        $view->setAttribute('batch_form', $this->formBuilder->getForm()->createView());
    }

    /**
     * @param ColumnInterface $column
     * @return array<string,string>
     */
    private function buildBatchActions(ColumnInterface $column): array
    {
        $batchActions = ['crud.list.batch.empty_choice' => ''];
        foreach ($column->getOption('actions') as $name => $actionOptions) {
            $batchActionUrl = $this->getBatchActionUrl($actionOptions);
            $batchActionLabel = $actionOptions['label'] ?? $name;
            $batchActions[(string) $batchActionLabel] = $batchActionUrl;
        }

        return $batchActions;
    }

    /**
     * @param array<string,mixed> $actionOptions
     * @return string
     */
    private function getBatchActionUrl(array $actionOptions): string
    {
        return $this->router->generate(
            $actionOptions['route_name'],
            $actionOptions['additional_parameters']
        );
    }

    /**
     * @param ColumnInterface $column
     * @param array<string,mixed> $batchActions
     */
    private function buildBatchForm(ColumnInterface $column, array $batchActions): void
    {
        if (count($batchActions) > 1) {
            $this->formBuilder->add(
                'action',
                ChoiceType::class,
                [
                    'choices' => $batchActions,
                    'translation_domain' => $column->getOption('translation_domain')
                ]
            );
            $this->formBuilder->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'crud.list.batch.confirm',
                    'translation_domain' => 'FSiAdminBundle'
                ]
            );
        }
    }

    private function getDefaultRouteName(Options $options): ?string
    {
        if (isset($options['element'])) {
            $this->validateElementFromOptions($options);

            return $this->getElementFromOption($options)->getRoute();
        }

        return null;
    }

    /**
     * @param Options $options
     * @param array<string,mixed> $additionalParameters
     * @return array<string,mixed>
     */
    private function normalizeAdditionalParameters(Options $options, array $additionalParameters): array
    {
        if (isset($options['element'])) {
            $this->validateElementFromOptions($options);

            $additionalParameters = $this->mergeAdditionalParametersWithElementFromOptions(
                $options,
                $additionalParameters
            );
        }

        return $this->mergeAdditionalParametersWithRedirectUri($options, $additionalParameters);
    }

    private function validateElementFromOptions(Options $options): void
    {
        if (false === $this->manager->hasElement($options['element'])) {
            throw new RuntimeException(sprintf('Unknown element "%s" specified in batch action', $options['element']));
        }
    }

    /**
     * @param Options $options
     * @param array<string,mixed> $additionalParameters
     * @return array<string,mixed>
     */
    private function mergeAdditionalParametersWithElementFromOptions(
        Options $options,
        array $additionalParameters
    ): array {
        return array_merge(
            ['element' => $this->getElementFromOption($options)->getId()],
            $this->getElementFromOption($options)->getRouteParameters(),
            $additionalParameters
        );
    }

    private function getElementFromOption(Options $options): Element
    {
        return $this->manager->getElement($options['element']);
    }

    /**
     * @param Options $options
     * @param array<string,mixed> $additionalParameters
     * @return array<string,mixed>
     */
    private function mergeAdditionalParametersWithRedirectUri(Options $options, array $additionalParameters): array
    {
        if (true === is_string($options['redirect_uri'])) {
            $additionalParameters['redirect_uri'] = $options['redirect_uri'];
        } elseif (false === $options['redirect_uri']) {
            return $additionalParameters;
        }

        $request = $this->getMasterRequest();
        if ($request->query->has('redirect_uri')) {
            $additionalParameters['redirect_uri'] = $this->getMasterRequest()->query->get('redirect_uri');
        } else {
            $additionalParameters['redirect_uri'] = $this->getMasterRequest()->getRequestUri();
        }

        return $additionalParameters;
    }

    private function getMasterRequest(): Request
    {
        $request = $this->requestStack->getMasterRequest();
        if (null === $request) {
            throw new RuntimeException("Batch actions are only available in request context");
        }

        return $request;
    }
}
