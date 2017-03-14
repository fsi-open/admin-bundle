<?php

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class BatchActionExtension extends ColumnAbstractTypeExtension
{
    /**
     * @var \FSi\Bundle\AdminBundle\Admin\ManagerInterface
     */
    protected $manager;

    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    protected $router;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @var \Symfony\Component\Form\FormBuilderInterface
     */
    protected $formBuilder;

    /**
     * @var \Symfony\Component\OptionsResolver\OptionsResolverInterface
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

    /**
     * @inheritdoc
     */
    public function getExtendedColumnTypes()
    {
        return ['batch'];
    }

    /**
     * @inheritdoc
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults([
            'actions' => [],
            'translation_domain' => 'FSiAdminBundle'
        ]);
        $column->getOptionsResolver()->setAllowedTypes('actions', ['array', 'null']);
        $column->getOptionsResolver()->setAllowedTypes('translation_domain', ['string']);
    }

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $this->buildBatchForm(
            $column,
            $this->buildBatchActions($column)
        );

        $view->setAttribute('batch_form', $this->formBuilder->getForm()->createView());
    }

    private function initActionOptions()
    {
        $this->actionOptionsResolver = new OptionsResolver();
        $this->actionOptionsResolver->setRequired([
            'route_name'
        ]);
        $this->actionOptionsResolver->setDefined([
            'element'
        ]);
        $this->actionOptionsResolver->setDefaults([
            'route_name' => function(Options $options) {
                return $this->getDefaultRouteName($options);
            },
            'additional_parameters' => [],
            'label' => null,
            'redirect_uri' => true,
        ]);
        $this->actionOptionsResolver->setNormalizer(
            'additional_parameters',
            function(Options $options, $value) {
                return $this->normalizeAdditionalParameters($options, $value);
            }
        );
        $this->actionOptionsResolver->setAllowedTypes('element', 'string');
        $this->actionOptionsResolver->setAllowedTypes('route_name', 'string');
        $this->actionOptionsResolver->setAllowedTypes('additional_parameters', 'array');
        $this->actionOptionsResolver->setAllowedTypes('label', ['string', 'null']);
        $this->actionOptionsResolver->setAllowedTypes('redirect_uri', ['string', 'bool']);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @return array
     */
    private function buildBatchActions(ColumnTypeInterface $column)
    {
        $batchActions = ['crud.list.batch.empty_choice'];

        foreach ($column->getOption('actions') as $name => $action) {
            $actionOptions = $this->actionOptionsResolver->resolve($action);

            $batchActions[$this->getBatchActionUrl($actionOptions)] =
                isset($actionOptions['label']) ? $actionOptions['label'] : $name;
        }

        return $batchActions;
    }

    /**
     * @param array $actionOptions
     * @return string
     */
    private function getBatchActionUrl(array $actionOptions)
    {
        return $this->router->generate(
            $actionOptions['route_name'],
            $actionOptions['additional_parameters']
        );
    }

    /**
     * @param ColumnTypeInterface $column
     * @param array $batchActions
     */
    private function buildBatchForm(ColumnTypeInterface $column, array $batchActions)
    {
        if (count($batchActions) > 1) {
            $this->formBuilder->add('action', 'choice', [
                'choices' => $batchActions,
                'translation_domain' => $column->getOption('translation_domain')
            ]);
            $this->formBuilder->add('submit', 'submit', [
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            ]);
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return null|string
     */
    private function getDefaultRouteName(Options $options)
    {
        if (isset($options['element'])) {
            $this->validateElementFromOptions($options);

            return $this->getElementFromOption($options)->getRoute();
        } else {
            return null;
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @param array $additionalParameters
     * @return array
     */
    private function normalizeAdditionalParameters(Options $options, array $additionalParameters)
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

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     */
    private function validateElementFromOptions(Options $options)
    {
        if (!$this->manager->hasElement($options['element'])) {
            throw new RuntimeException(sprintf(
                'Unknown element "%s" specified in batch action',
                $options['element']
            ));
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @param array $additionalParameters
     * @return array
     */
    private function mergeAdditionalParametersWithElementFromOptions(Options $options, array $additionalParameters)
    {
        $additionalParameters = array_merge(
            ['element' => $this->getElementFromOption($options)->getId()],
            $this->getElementFromOption($options)->getRouteParameters(),
            $additionalParameters
        );
        return $additionalParameters;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return \FSi\Bundle\AdminBundle\Admin\Element
     */
    private function getElementFromOption(Options $options)
    {
        return $this->manager->getElement($options['element']);
    }

    /**
     * @param Options $options
     * @param array $additionalParameters
     * @return mixed
     */
    private function mergeAdditionalParametersWithRedirectUri(Options $options, array $additionalParameters)
    {
        if (is_string($options['redirect_uri'])) {
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

    /**
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    private function getMasterRequestQuery()
    {
        return $this->requestStack->getMasterRequest()->query;
    }
}
