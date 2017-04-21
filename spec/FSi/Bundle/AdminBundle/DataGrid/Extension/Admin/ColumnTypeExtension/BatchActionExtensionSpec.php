<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class BatchActionExtensionSpec extends ObjectBehavior
{
    function let(
        ManagerInterface $manager,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        RequestStack $requestStack,
        Request $request,
        ParameterBag $queryAttributes,
        FormInterface $form,
        FormView $formView
    ) {
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
        $this->getExtendedColumnTypes()->shouldReturn(['batch']);
    }

    function it_adds_actions_options(ColumnTypeInterface $column, OptionsResolver $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefaults(['actions' => [], 'translation_domain' => 'FSiAdminBundle'])
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('actions', ['array', 'null'])->shouldBeCalled();
        $optionsResolver->setAllowedTypes('translation_domain', ['string'])->shouldBeCalled();

        $this->initOptions($column);
    }

    function it_does_not_add_batch_actions_to_form_when_none_are_defined(
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn([]);
        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_throws_exception_when_wrong_action_option_is_passed(
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn([['wrong_option' => 'value']]);

        $this->shouldThrow()
            ->during('buildHeaderView', [$column, $view]);
    }

    function it_throws_exception_when_non_existing_element_is_passed(
        ManagerInterface $manager,
        FormBuilderInterface $formBuilder,
        ColumnTypeInterface $column,
        HeaderViewInterface $view,
        FormView $formView
    ) {
        $column->getOption('actions')->willReturn([
            [
                'element' => 'some_batch_element_id',
                'label' => 'batch_action_label'
            ]
        ]);
        $manager->hasElement('some_batch_element_id')->willReturn(false);

        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldNotBeCalled();

        $this->shouldThrow(new RuntimeException('Unknown element "some_batch_element_id" specified in batch action'))
            ->during('buildHeaderView', [$column, $view]);
    }

    function it_adds_actions_choice_to_form_when_actions_are_defined(
        ManagerInterface $manager,
        BatchElement $batchElement,
        ParameterBag $queryAttributes,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn([
            [
                'element' => 'some_batch_element_id',
                'additional_parameters' => ['some_additional_parameter' => 'some_value'],
                'label' => 'batch_action_label'
            ]
        ]);

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(['element' => 'some_batch_element_id']);
        $queryAttributes->has('redirect_uri')->willReturn(true);
        $queryAttributes->get('redirect_uri')->willReturn('some_redirect_uri');

        $router->generate(
            'fsi_admin_batch',
            [
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value',
                'redirect_uri' => 'some_redirect_uri'
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add('action', 'choice', [
            'choices' => [
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            ],
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();

        $formBuilder->add('submit', 'submit', [
            'label' => 'crud.list.batch.confirm',
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_adds_redirect_uri_to_actions_when_no_redirect_uri_is_defined(
        ManagerInterface $manager,
        BatchElement $batchElement,
        ParameterBag $queryAttributes,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view,
        Request $request
    ) {
        $column->getOption('actions')->willReturn([
            [
                'element' => 'some_batch_element_id',
                'additional_parameters' => ['some_additional_parameter' => 'some_value'],
                'label' => 'batch_action_label'
            ]
        ]);

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(['element' => 'some_batch_element_id']);
        $queryAttributes->has('redirect_uri')->willReturn(false);
        $request->getRequestUri()->willReturn('current_request_uri');

        $router->generate(
            'fsi_admin_batch',
            [
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value',
                'redirect_uri' => 'current_request_uri'
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add('action', 'choice', [
            'choices' => [
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            ],
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();
        $formBuilder->add('submit', 'submit', [
            'label' => 'crud.list.batch.confirm',
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_does_not_add_redirect_uri_to_actions_when_redirect_uri_option_is_set_to_false(
        ManagerInterface $manager,
        BatchElement $batchElement,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn([
            [
                'element' => 'some_batch_element_id',
                'additional_parameters' => ['some_additional_parameter' => 'some_value'],
                'label' => 'batch_action_label',
                'redirect_uri' => false,
            ]
        ]);

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $manager->getElement('some_batch_element_id')->willReturn($batchElement);
        $batchElement->getId()->willReturn('some_batch_element_id');
        $batchElement->getRoute()->willReturn('fsi_admin_batch');
        $batchElement->getRouteParameters()->willReturn(['element' => 'some_batch_element_id']);

        $router->generate(
            'fsi_admin_batch',
            [
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value'
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add('action', 'choice', [
            'choices' => [
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            ],
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();
        $formBuilder->add('submit', 'submit', [
            'label' => 'crud.list.batch.confirm',
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_allows_to_pass_route_name_and_additional_parameters_to_batch_action(
        ParameterBag $queryAttributes,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn([
            'action_name' => [
                'route_name' => 'fsi_admin_custom_batch',
                'additional_parameters' => ['element' => 'some_batch_element_id', 'param' => 'value']
            ]
        ]);

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $queryAttributes->has('redirect_uri')->willReturn(true);
        $queryAttributes->get('redirect_uri')->willReturn('some_redirect_uri');

        $router->generate(
            'fsi_admin_custom_batch',
            [
                'element' => 'some_batch_element_id',
                'param' => 'value',
                'redirect_uri' => 'some_redirect_uri'
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add('action', 'choice', [
            'choices' => [
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'action_name'
            ],
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();
        $formBuilder->add('submit', 'submit', [
            'label' => 'crud.list.batch.confirm',
            'translation_domain' => 'FSiAdminBundle'
        ])->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
