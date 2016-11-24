<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Bundle\AdminBundle\Form\FeatureHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceFormBuilderSpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder $mapBuilder
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository $valueRepository
     * @param \FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType $resource
     */
    function let($mapBuilder, $formFactory, $element, $valueRepository, $resource)
    {
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

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     */
    function it_throw_exception_when_resource_key_is_not_resource_group_key($element)
    {
        $element->getKey()->willReturn('resources.resource_key');

        $this->shouldThrow(
            new RuntimeException("resources.resource_key its not a resource group key")
        )->during(
            'build',
            array($element)
        );
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $formFactory
     * @param \Symfony\Component\Form\Test\FormBuilderInterface $formBuilder
     * @param \FSi\Bundle\AdminBundle\Admin\ResourceRepository\GenericResourceElement $element
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValueRepository $valueRepository
     * @param \FSi\Bundle\ResourceRepositoryBundle\Model\ResourceValue $resourceValue
     * @param \Symfony\Component\Form\FormInterface $form
     */
    function it_builds_form_for_resource_group(
        $formFactory,
        $formBuilder,
        $element,
        $valueRepository,
        $resourceValue,
        $form
    ) {
        $element->getKey()->willReturn('resources');
        $valueRepository->get('resources.resource_key')->willReturn($resourceValue);

        $formFactory
            ->createBuilder(
                FeatureHelper::getFormType('Symfony\Component\Form\Extension\Core\Type\FormType', 'form'),
                array('resources_resource_key' => $resourceValue),
                array('form_options')
            )
            ->willReturn($formBuilder);

        $formBuilder
            ->add(
                'resources_resource_key',
                FeatureHelper::getFormType('FSi\Bundle\ResourceRepositoryBundle\Form\Type\ResourceType', 'resource'),
                array('resource_key' => 'resources.resource_key')
            )
            ->shouldBeCalled();

        $formBuilder->getForm()->willReturn($form);

        $this->build($element)->shouldReturn($form);
    }
}
