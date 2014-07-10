<?php

namespace spec\FSi\Bundle\AdminBundle\DataGrid\Extension\Admin\ColumnTypeExtension;

use FSi\Bundle\AdminBundle\Admin\Manager;
use FSi\Component\DataGrid\Column\ColumnTypeInterface;
use FSi\Component\DataGrid\Column\HeaderViewInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Test\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\RouterInterface;

class BatchActionExtensionSpec extends ObjectBehavior
{
    function let(
        Manager $manager,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        Form $form,
        FormView $formView
    ) {
        $this->beConstructedWith($manager, $router, $formBuilder);
        $formBuilder->getForm()->willReturn($form);
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

    function it_adds_actions_options(ColumnTypeInterface $column, OptionsResolverInterface $optionsResolver)
    {
        $column->getOptionsResolver()->willReturn($optionsResolver);

        $optionsResolver->setDefaults(array('actions' => array()))->shouldBeCalled();
        $optionsResolver->setAllowedTypes(array('actions' => array('array', 'null')))->shouldBeCalled();

        $this->initOptions($column);
    }

    function it_does_not_add_batch_actions_to_form_when_none_are_defined(
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn(array());
        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_throws_exception_when_wrong_action_option_is_passed(
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'wrong_option' => 'value'
            )
        ));

        $this->shouldThrow('Symfony\Component\OptionsResolver\Exception\InvalidOptionsException')
            ->during('buildHeaderView', array($column, $view));
    }

    function it_does_not_add_batch_actions_to_form_when_non_existing_element_is_passed(
        Manager $manager,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'label' => 'batch_action_label'
            )
        ));
        $manager->hasElement('some_batch_element_id')->willReturn(false);

        $formBuilder->add(Argument::any())->shouldNotBeCalled();
        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }

    function it_adds_actions_choice_to_form_when_actions_are_defined(
        Manager $manager,
        RouterInterface $router,
        FormBuilderInterface $formBuilder,
        FormView $formView,
        ColumnTypeInterface $column,
        HeaderViewInterface $view
    ) {
        $column->getOption('actions')->willReturn(array(
            array(
                'element' => 'some_batch_element_id',
                'label' => 'batch_action_label'
            )
        ));
        $manager->hasElement('some_batch_element_id')->willReturn(true);
        $router->generate('fsi_admin_batch', array('element' => 'some_batch_element_id'))
            ->willReturn('path_to_batch_action');

        $formBuilder->add('action', 'choice', array(
            'choices' => array(
                0 => 'crud.list.batch.empty_choice',
                'path_to_batch_action' => 'batch_action_label'
            ),
            'translation_domain' => 'FSiAdminBundle'
        ))->willReturn();
        $formBuilder->add('submit', 'submit', array(
            'label' => 'crud.list.batch.confirm',
            'translation_domain' => 'FSiAdminBundle'
        ))->willReturn();

        $view->setAttribute('batch_form', $formView)->shouldBeCalled();

        $this->buildHeaderView($column, $view);
    }
}
