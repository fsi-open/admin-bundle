<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use Closure;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\ManagerInterface;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\Column\ColumnInterface;
use FSi\Component\DataGrid\Column\ColumnTypeExtensionInterface;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use FSi\Component\DataGrid\ColumnType\Batch;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
    public function let(
        ManagerInterface $manager,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        RequestStack $requestStack,
        Request $request,
        ParameterBag $queryAttributes,
        FormInterface $form,
        FormView $formView
    ): void {
        $this->beConstructedWith($manager, $requestStack, $router, $formBuilder);
        $formBuilder->getForm()->willReturn($form);
        $requestStack->getMasterRequest()->willReturn($request);
        $request->query = $queryAttributes;
        $form->createView()->willReturn($formView);
    }

    public function it_is_column_extension(): void
    {
        $this->shouldBeAnInstanceOf(ColumnTypeExtensionInterface::class);
    }

    public function it_should_extend_batch_column(): void
    {
        $this->getExtendedColumnTypes()->shouldReturn([Batch::class]);
    }

    public function it_adds_actions_options(ColumnTypeInterface $column, OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(['translation_domain' => 'FSiAdminBundle'])
            ->shouldBeCalled();
        $optionsResolver->setAllowedTypes('actions', ['array'])->shouldBeCalled();
        $optionsResolver->setAllowedTypes('translation_domain', ['string'])->shouldBeCalled();
        $optionsResolver->setDefault('actions', Argument::type(Closure::class))->shouldBeCalled();

        $this->initOptions($optionsResolver);
    }

    public function it_does_not_add_batch_actions_to_form_when_none_are_defined(
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnInterface $column,
        HeaderViewInterface $view
    ): void {
        $column->getOption('actions')->willReturn([]);
        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    public function it_throws_exception_when_wrong_action_option_is_passed(
        ColumnInterface $column,
        HeaderViewInterface $view
    ): void {
        $column->getOption('actions')->willReturn([['wrong_option' => 'value']]);

        $this->shouldThrow()
            ->during('buildHeaderView', [$column, $view]);
    }

    public function it_adds_actions_choice_to_form_when_actions_are_defined(
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnInterface $column,
        HeaderViewInterface $view
    ): void {
        $column->getOption('actions')->willReturn(
            [
                [
                    'element' => 'some_batch_element_id',
                    'additional_parameters' => [
                        'element' => 'some_batch_element_id',
                        'some_additional_parameter' => 'some_value',
                        'redirect_uri' => 'some_redirect_uri',
                    ],
                    'label' => 'batch_action_label',
                    'route_name' => 'fsi_admin_batch',
                    'redirect_uri' => true,
                ],
            ]
        );

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $router->generate(
            'fsi_admin_batch',
            [
                'element' => 'some_batch_element_id',
                'some_additional_parameter' => 'some_value',
                'redirect_uri' => 'some_redirect_uri',
            ]
        )->shouldBeCalled()->willReturn('path_to_batch_action');

        $expectedChoices = [
            'crud.list.batch.empty_choice' => '',
            'batch_action_label' => 'path_to_batch_action',
        ];

        $formBuilder->add(
            'action',
            ChoiceType::class,
            [
                'choices' => $expectedChoices,
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();

        $formBuilder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    public function it_adds_redirect_uri_to_actions_when_no_redirect_uri_is_defined(
        ManagerInterface $manager,
        BatchElement $batchElement,
        ParameterBag $queryAttributes,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnInterface $column,
        HeaderViewInterface $view,
        Request $request
    ): void {
        $column->getOption('actions')->willReturn(
            [
                [
                    'element' => 'some_batch_element_id',
                    'additional_parameters' => [
                        'element' => 'some_batch_element_id',
                        'some_additional_parameter' => 'some_value',
                        'redirect_uri' => 'current_request_uri',
                    ],
                    'label' => 'batch_action_label',
                    'route_name' => 'fsi_admin_batch',
                ],
            ]
        );

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
                'redirect_uri' => 'current_request_uri',
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add(
            'action',
            ChoiceType::class,
            [
                'choices' => [
                    'crud.list.batch.empty_choice' => '',
                    'batch_action_label' => 'path_to_batch_action',
                ],
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();
        $formBuilder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    public function it_does_not_add_redirect_uri_to_actions_when_redirect_uri_option_is_set_to_false(
        ManagerInterface $manager,
        BatchElement $batchElement,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnInterface $column,
        HeaderViewInterface $view
    ): void {
        $column->getOption('actions')->willReturn(
            [
                [
                    'element' => 'some_batch_element_id',
                    'additional_parameters' => [
                        'element' => 'some_batch_element_id',
                        'some_additional_parameter' => 'some_value',
                    ],
                    'label' => 'batch_action_label',
                    'redirect_uri' => false,
                    'route_name' => 'fsi_admin_batch',
                ],
            ]
        );

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
                'some_additional_parameter' => 'some_value',
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add(
            'action',
            ChoiceType::class,
            [
                'choices' => [
                    'crud.list.batch.empty_choice' => '',
                    'batch_action_label' => 'path_to_batch_action',
                ],
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();
        $formBuilder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    public function it_allows_to_pass_route_name_and_additional_parameters_to_batch_action(
        ParameterBag $queryAttributes,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnInterface $column,
        HeaderViewInterface $view
    ): void {
        $column->getOption('actions')->willReturn(
            [
                'action_name' => [
                    'route_name' => 'fsi_admin_custom_batch',
                    'additional_parameters' => [
                        'element' => 'some_batch_element_id',
                        'param' => 'value',
                        'redirect_uri' => 'some_redirect_uri',
                    ],
                ],
            ]
        );

        $column->getOption('translation_domain')->willReturn('FSiAdminBundle');

        $queryAttributes->has('redirect_uri')->willReturn(true);
        $queryAttributes->get('redirect_uri')->willReturn('some_redirect_uri');

        $router->generate(
            'fsi_admin_custom_batch',
            [
                'element' => 'some_batch_element_id',
                'param' => 'value',
                'redirect_uri' => 'some_redirect_uri',
            ]
        )->willReturn('path_to_batch_action');

        $formBuilder->add(
            'action',
            ChoiceType::class,
            [
                'choices' => [
                    'crud.list.batch.empty_choice' => '',
                    'action_name' => 'path_to_batch_action',
                ],
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();
        $formBuilder->add(
            'submit',
            SubmitType::class,
            [
                'label' => 'crud.list.batch.confirm',
                'translation_domain' => 'FSiAdminBundle',
            ]
        )->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
