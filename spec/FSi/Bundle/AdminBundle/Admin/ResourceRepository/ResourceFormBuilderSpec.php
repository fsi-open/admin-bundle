<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminBundle\Form\TypeSolver;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

class ResourceFormBuilderSpec extends ObjectBehavior
{
    function let(
        MapBuilder $mapBuilder,
        FormFactoryInterface $formFactory,
        GenericResourceElement $element,
        ResourceValueRepository $valueRepository,
        TextType $resource
    ) {
        $mapBuilder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));
        $resource->getName()->willReturn('resources.resource_key');

        $element->getRepository()->willReturn($valueRepository);
        $element->getResourceFormOptions()->willReturn(array('form_options'));

        $this->beConstructedWith($formFactory, $mapBuilder);
    }

    function it_throw_exception_when_resource_key_is_not_resource_group_key(GenericResourceElement $element)
    {
        $element->getKey()->willReturn('resources.resource_key');

        $this->shouldThrow(
            new RuntimeException("resources.resource_key its not a resource group key")
        )->during('build', array($element));
    }

    function it_builds_form_for_resource_group(
        FormFactoryInterface $formFactory,
        FormBuilderInterface $formBuilder,
        GenericResourceElement $element,
        ResourceValueRepository $valueRepository,
        ResourceValue $resourceValue,
        FormInterface $form
    ) {
        $element->getKey()->willReturn('resources');
        $valueRepository->get('resources.resource_key')->willReturn($resourceValue);

        $formFactory
            ->createBuilder(
                TypeSolver::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
                array('resources_resource_key' => $resourceValue),
                array('form_options')
            )
            ->willReturn($formBuilder);

        $formBuilder
            ->add(
                'resources_resource_key',
                TypeSolver::getFormType('FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType', 'resource'),
                array('resource_key' => 'resources.resource_key')
            )
            ->shouldBeCalled();

        $formBuilder->getForm()->willReturn($form);

        $this->build($element)->shouldReturn($form);
    }
}
