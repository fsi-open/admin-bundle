<?php

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Manager;
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
     * @var \FSi\Bundle\AdminBundle\Admin\Manager
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
     * @param \Symfony\Component\Form\FormBuilderInterface $formBuilder
     */
    public function __construct(
        Manager $manager,
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
        return array('batch');
    }

    /**
     * @inheritdoc
     */
    public function initOptions(ColumnTypeInterface $column)
    {
        $column->getOptionsResolver()->setDefaults(array('actions' => array()));
        $column->getOptionsResolver()->setAllowedTypes(array(
            'actions' => array('array', 'null')
        ));
    }

    public function buildHeaderView(ColumnTypeInterface $column, HeaderViewInterface $view)
    {
        $this->buildBatchForm(
            $this->buildBatchActions($column)
        );

        $view->setAttribute('batch_form', $this->formBuilder->getForm()->createView());
    }

    private function initActionOptions()
    {
        $this->actionOptionsResolver = new OptionsResolver();
        $this->actionOptionsResolver->setRequired(array(
            'route_name'
        ));
        $this->actionOptionsResolver->setOptional(array(
            'element'
        ));
        $self = $this;
        $this->actionOptionsResolver->setDefaults(array(
            'route_name' => function(Options $options) use ($self) {
                return $self->getDefaultRouteName($options);
            },
            'additional_parameters' => array(),
            'label' => null
        ));
        $this->actionOptionsResolver->setNormalizers(array(
            'additional_parameters' => function(Options $options, $value) use ($self) {
                return $self->normalizeAdditionalParameters($options, $value);
            },
        ));
        $this->actionOptionsResolver->setAllowedTypes(array(
            'element' => 'string',
            'route_name' => 'string',
            'additional_parameters' => 'array',
            'label' => array('string', 'null')
        ));
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @return array
     */
    private function buildBatchActions(ColumnTypeInterface $column)
    {
        $batchActions = array('crud.list.batch.empty_choice');

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
     * @param array $batchActions
     */
    private function buildBatchForm(array $batchActions)
    {
        if (count($batchActions) > 1) {
            $this->formBuilder->add('action', 'choice', array(
                'choices' => $batchActions,
                'translation_domain' => 'FSiAdminBundle'
            ));
            $this->formBuilder->add('submit', 'submit', array(
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            ));
        }
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     * @return null|string
     */
    private function getDefaultRouteName(Options $options)
    {
        if ($options->has('element')) {
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
        if ($options->has('element')) {
            $this->validateElementFromOptions($options);

            $additionalParameters = $this->mergeAdditionalParametersWithElementFromOptions(
                $options,
                $additionalParameters
            );
        }

        return $this->mergeAdditionalParametersWithRedirectUri($additionalParameters);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\Options $options
     */
    private function validateElementFromOptions(Options $options)
    {
        if (!$this->manager->hasElement($options->get('element'))) {
            throw new RuntimeException(sprintf(
                'Unknown element "%s" specified in batch action',
                $options->get('element')
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
            array('element' => $this->getElementFromOption($options)->getId()),
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
        return $this->manager->getElement($options->get('element'));
    }

    /**
     * @param array $additionalParameters
     * @return mixed
     */
    private function mergeAdditionalParametersWithRedirectUri(array $additionalParameters)
    {
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
