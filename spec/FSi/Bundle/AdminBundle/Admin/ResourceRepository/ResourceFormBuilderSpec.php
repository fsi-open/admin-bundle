<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement;
use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue;
use FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\FormBuilderInterface;

class ResourceFormBuilderSpec extends ObjectBehavior
{
    function let(
        MapBuilder $mapBuilder,
        FormFactoryInterface $formFactory
    ) {
        $this->beConstructedWith($formFactory, $mapBuilder);
    }

    function it_throw_exception_when_resource_key_is_not_resource_group_key(
        MapBuilder $mapBuilder,
        GenericResourceElement $element,
        TextType $resource
    ) {
        $element->getKey()->willReturn('resources.resource_key');
        $mapBuilder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));

        $this->shouldThrow(
            new RuntimeException("resources.resource_key its not a resource group key")
        )->during(
            'build',
            array($element)
        );
    }

    function it_builds_form_for_resource_group(
        MapBuilder $mapBuilder,
        TextType $resource,
        FormFactoryInterface $formFactory,
        FormBuilderInterface $formBuilder,
        GenericResourceElement $element,
        ResourceValueRepository $valueRepository,
        ResourceValue $resourceValue,
        FormInterface $form
    ) {
        $element->getKey()->willReturn('resources');
        $element->getRepository()->willReturn($valueRepository);
        $element->getResourceFormOptions()->willReturn(array('form_options'));
        $mapBuilder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));
        $resource->getName()->willReturn('resources.resource_key');
        $valueRepository->get('resources.resource_key')->willReturn($resourceValue);

        $formFactory
            ->createBuilder('form', array('resources_resource_key' => $resourceValue), array('form_options'))
            ->willReturn($formBuilder);

        $formBuilder
            ->add('resources_resource_key', 'resource', array('resource_key' => 'resources.resource_key'))
            ->shouldBeCalled();

        $formBuilder->getForm()->willReturn($form);

        $this->build($element)->shouldReturn($form);
    }
}
