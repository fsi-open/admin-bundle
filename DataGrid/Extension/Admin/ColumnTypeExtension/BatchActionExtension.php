<?php

declare(strict_types=1);

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Element;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class BatchActionExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * @var OptionsResolver
     */
    protected $actionOptionsResolver;

    /**
     * @param ManagerInterface $manager
     * @param RequestStack $requestStack
     * @param RouterInterface $router
     * @param FormBuilderInterface $formBuilder
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
        $this->initActionOptions();
    }

    public function getExtendedColumnTypes(): array
    {
        return ['batch'];
    }

    public function initOptions(ColumnTypeInterface $column): void
    {
        $column->getOptionsResolver()->setDefaults([
            'actions' => [],
            'translation_domain' => 'FSiAdminBundle'
        ]);
        $column->getOptionsResolver()->setAllowedTypes('actions', ['array', 'null']);
        $column->getOptionsResolver()->setAllowedTypes('translation_domain', ['string']);
    }

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view): void
    {
        $this->buildBatchForm(
            $column,
            $this->buildBatchActions($column)
        );

        $view->setAttribute('batch_form', $this->formBuilder->getForm()->createView());
    }

    private function initActionOptions(): void
    {
        $this->actionOptionsResolver = new OptionsResolver();
        $this->actionOptionsResolver->setRequired([
            'route_name'
        ]);
        $this->actionOptionsResolver->setDefined([
            'element'
        ]);
        $this->actionOptionsResolver->setDefaults([
            'route_name' => function (Options $options) {
                return $this->getDefaultRouteName($options);
            },
            'additional_parameters' => [],
            'label' => null,
            'redirect_uri' => true,
        ]);
        $this->actionOptionsResolver->setNormalizer(
            'additional_parameters',
            function (Options $options, $value) {
                return $this->normalizeAdditionalParameters($options, $value);
            }
        );
        $this->actionOptionsResolver->setAllowedTypes('element', 'string');
        $this->actionOptionsResolver->setAllowedTypes('route_name', 'string');
        $this->actionOptionsResolver->setAllowedTypes('additional_parameters', 'array');
        $this->actionOptionsResolver->setAllowedTypes('label', ['string', 'null']);
        $this->actionOptionsResolver->setAllowedTypes('redirect_uri', ['string', 'bool']);
    }

    private function buildBatchActions(ColumnTypeInterface $column): array
    {
        $batchActions = ['crud.list.batch.empty_choice' => ''];
        foreach ($column->getOption('actions') as $name => $action) {
            $actionOptions = $this->actionOptionsResolver->resolve($action);

            $batchActionUrl = $this->getBatchActionUrl($actionOptions);
            $batchActionLabel = $actionOptions['label'] ?? $name;
            $batchActions[$batchActionLabel] = $batchActionUrl;
        }

        return $batchActions;
    }

    private function getBatchActionUrl(array $actionOptions): string
    {
        return $this->router->generate(
            $actionOptions['route_name'],
            $actionOptions['additional_parameters']
        );
    }

    private function buildBatchForm(ColumnTypeInterface $column, array $batchActions): void
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

    private function mergeAdditionalParametersWithRedirectUri(Options $options, array $additionalParameters): array
    {
        if (true === is_string($options['redirect_uri'])) {
            $additionalParameters['redirect_uri'] = $options['redirect_uri'];
        } elseif ($options['redirect_uri'] === false) {
            return $additionalParameters;
        }

        if ($this->getMasterRequestQuery()->has('redirect_uri')) {
            $additionalParameters['redirect_uri'] = $this->getMasterRequestQuery()->get('redirect_uri');
        } else {
            $additionalParameters['redirect_uri'] = $this->requestStack->getMasterRequest()->getRequestUri();
        }

        return $additionalParameters;
    }

    private function getMasterRequestQuery(): ParameterBag
    {
        return $this->requestStack->getMasterRequest()->query;
    }
}
