<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BatchActionExtensionSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryAttributes
     * @param \Symfony\Component\Form\Form $form
     * @param \Symfony\Component\Form\FormView $formView
     */
    function let($manager, $router, $formBuilder, $requestStack, $request, $queryAttributes, $form, $formView)
    {
        $this->beConstructedWith($manager, $requestStack, $router, $formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $requestStack->getMasterRequest()->willReturn($request);
        $request->query = $queryAttributes;
        $form->createView()->willReturn($formView);
    }

    function it_is_column_extension()
    {
        $this->shouldBeAnInstanceOf('FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface');
    }

    function it_should_extend_batch_column()
    {
        $this->getExtendedColumnTypes()->shouldReturn(array('batch'));
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $optionsResolver
     */
    function it_adds_actions_options($column, $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefaults(array('actions' => array(), 'translation_domain' => 'FSiAdminBundle'))
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('actions', array('array', 'null'))->shouldBeCalled();
        $optionsResolver->setAllowedTypes('translation_domain', array('string'))->shouldBeCalled();

        $this->initOptions($column);
    }

    /**
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_does_not_add_batch_actions_to_form_when_none_are_defined($formBuilder, $formView, $column, $view)
    {
        $column->getOption('actions')->willReturn(array());
        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    /**
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_throws_exception_when_wrong_action_option_is_passed($column, $view)
    {
        $column->getOption('actions')->willReturn(array(
            array(
                'wrong_option' => 'value'
            )
        ));

        $this->shouldThrow()
            ->during('buildHeaderView', array($column, $view));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_throws_exception_when_non_existing_element_is_passed($manager, $formBuilder, $formView, $column, $view)
    {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'label' => 'batch_action_label'
            )
        ));
        $manager->hasElement('some_batch_element_id')->willReturn(false);

        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldNotBeCalled();

        $this->shouldThrow(new RuntimeException('Unknown element "some_batch_element_id" specified in batch action'))
            ->during('buildHeaderView', array($column, $view));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $batchElement
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryAttributes
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_adds_actions_choice_to_form_when_actions_are_defined(
        $manager,
        $batchElement,
        $queryAttributes,
        $router,
        $formBuilder,
        $formView,
        $column,
        $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'additional_parameters' => array('some_additional_parameter' => 'some_value'),
                'label' => 'batch_action_label'
            )
        ));

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(array('element' => 'some_batch_element_id'));
        $queryAttributes->has('redirect_uri')->willReturn(true);
        $queryAttributes->get('redirect_uri')->willReturn('some_redirect_uri');

        $router->generate(
            'fsi_admin_batch',
            array(
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value',
                'redirect_uri' => 'some_redirect_uri'
            )
        )->willReturn('path_to_batch_action');

        if (TypeSolver::isChoicesAsValuesOptionTrueByDefault()) {
            $expectedChoices = array(
                'crud.list.batch.empty_choice' => '',
                'batch_action_label' => 'path_to_batch_action'
            );
        } else {
            $expectedChoices = array(
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            );
        }

        $formBuilder->add(
            'action',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\ChoiceType', 'choice'),
            array(
                'choices' => $expectedChoices,
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();

        $formBuilder->add(
            'submit',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\SubmitType', 'submit'),
            array(
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $batchElement
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryAttributes
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_adds_redirect_uri_to_actions_when_no_redirect_uri_is_defined(
        $manager,
        $batchElement,
        $request,
        $queryAttributes,
        $router,
        $formBuilder,
        $formView,
        $column,
        $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'additional_parameters' => array('some_additional_parameter' => 'some_value'),
                'label' => 'batch_action_label'
            )
        ));

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(array('element' => 'some_batch_element_id'));
        $queryAttributes->has('redirect_uri')->willReturn(false);
        $request->getRequestUri()->willReturn('current_request_uri');

        $router->generate(
            'fsi_admin_batch',
            array(
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value',
                'redirect_uri' => 'current_request_uri'
            )
        )->willReturn('path_to_batch_action');

        if (TypeSolver::isChoicesAsValuesOptionTrueByDefault()) {
            $expectedChoices = array(
                'crud.list.batch.empty_choice' => '',
                'batch_action_label' => 'path_to_batch_action'
            );
        } else {
            $expectedChoices = array(
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            );
        }

        $formBuilder->add(
            'action',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\ChoiceType', 'choice'),
            array(
                'choices' => $expectedChoices,
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();
        $formBuilder->add(
            'submit',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\SubmitType', 'submit'),
            array(
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\Manager $manager
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement $batchElement
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryAttributes
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_does_not_add_redirect_uri_to_actions_when_redirect_uri_option_is_set_to_false(
        $manager,
        $batchElement,
        $router,
        $formBuilder,
        $formView,
        $column,
        $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'additional_parameters' => array('some_additional_parameter' => 'some_value'),
                'label' => 'batch_action_label',
                'redirect_uri' => false,
            )
        ));

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(array('element' => 'some_batch_element_id'));

        $router->generate(
            'fsi_admin_batch',
            array(
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value'
            )
        )->willReturn('path_to_batch_action');

        if (TypeSolver::isChoicesAsValuesOptionTrueByDefault()) {
            $expectedChoices = array(
                'crud.list.batch.empty_choice' => '',
                'batch_action_label' => 'path_to_batch_action'
            );
        } else {
            $expectedChoices = array(
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            );
        }

        $formBuilder->add(
            'action',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\ChoiceType', 'choice'),
            array(
                'choices' => $expectedChoices,
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();
        $formBuilder->add(
            'submit',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\SubmitType', 'submit'),
            array(
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\ParameterBag $queryAttributes
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \Symfony\Component\Form\FormView $formView
     * @param \FSi\Component\DataGrid\Column\ColumnTypeInterface $column
     * @param \FSi\Component\DataGrid\Column\HeaderViewInterface $view
     */
    function it_allows_to_pass_route_name_and_additional_parameters_to_batch_action(
        $queryAttributes,
        $router,
        $formBuilder,
        $formView,
        $column,
        $view
    ) {
        $column->getOption('actions')->willReturn(array(
            'action_name' => array(
                'route_name' => 'fsi_admin_custom_batch',
                'additional_parameters' => array('element' => 'some_batch_element_id', 'param' => 'value')
            )
        ));

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $queryAttributes->has('redirect_uri')->willReturn(true);
        $queryAttributes->get('redirect_uri')->willReturn('some_redirect_uri');

        $router->generate(
            'fsi_admin_custom_batch',
            array(
                'element' => 'some_batch_element_id',
                'param' => 'value',
                'redirect_uri' => 'some_redirect_uri'
            )
        )->willReturn('path_to_batch_action');

        if (TypeSolver::isChoicesAsValuesOptionTrueByDefault()) {
            $expectedChoices = array(
                'crud.list.batch.empty_choice' => '',
                'action_name' => 'path_to_batch_action'
            );
        } else {
            $expectedChoices = array(
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'action_name'
            );
        }

        $formBuilder->add(
            'action',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\ChoiceType', 'choice'),
            array(
                'choices' => $expectedChoices,
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();
        $formBuilder->add(
            'submit',
            TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\SubmitType', 'submit'),
            array(
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle'
            )
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
