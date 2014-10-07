<?php

namespace FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Component\DataGrid\Column\ColumnAbstractTypeExtension;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
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
        $actions = $column->getOption('actions');

        $choices = array('crud.list.batch.empty_choice');
        foreach ($actions as $action) {
            $action = $this->actionOptionsResolver->resolve($action);
            if (!$this->manager->hasElement($action['element'])) {
                continue;
            }

            $element = $this->manager->getElement($action['element']);
            $parameters = $element->getRouteParameters();
            if ($this->requestStack->getMasterRequest()->query->has('redirect_uri')) {
                $parameters['redirect_uri'] = $this->requestStack->getMasterRequest()->query->get('redirect_uri');
            }

            $path = $this->router->generate($element->getRoute(), $parameters);
            $choices[$path] = $action['label'];
        }

        if (count($choices) > 1) {
            $this->formBuilder->add(
                'action',
                'choice',
                array(
                    'choices' => $choices,
                    'translation_domain' => 'FSiAdminBundle'
                )
            );
            $this->formBuilder->add(
                'submit',
                'submit',
                array(
                    'label' => 'crud.list.batch.confirm',
                    'translation_domain' => 'FSiAdminBundle'
                )
            );
        }

        $view->setAttribute('batch_form', $this->formBuilder->getForm()->createView());
    }

    protected function initActionOptions()
    {
        $this->actionOptionsResolver = new OptionsResolver();
        $this->actionOptionsResolver->setRequired(array('element', 'label'));
        $this->actionOptionsResolver->setAllowedTypes(array(
            'element' => 'string',
            'label' => 'string'
        ));
    }
}
