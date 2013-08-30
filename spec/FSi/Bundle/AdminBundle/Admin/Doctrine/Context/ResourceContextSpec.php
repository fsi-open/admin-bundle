<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use Doctrine\Common\Persistence\ObjectManager;
use FSi\Bundle\AdminBundle\Event\ResourceEvents;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\ResourceRepositoryBundle\Entity\ResourceRepository;
use FSi\Bundle\ResourceRepositoryBundle\Repository\MapBuilder;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\EmailType;
use FSi\Bundle\ResourceRepositoryBundle\Repository\Resource\Type\TextType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FSi\Bundle\AdminBundle\Admin\Doctrine\ResourceElement;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class ResourceContextSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, ResourceElement $element, MapBuilder $builder, FormFactory $formFactory,
        Router $router, FormBuilder $formBuilder, Form $form)
    {
        $this->beConstructedWith($dispatcher, $element, $builder, $formFactory, $router);

        $builder->getMap()->willReturn(array(
            'resources' => array()
        ));
        $element->getResourceFormOptions()->willReturn(array());
        $element->getKey()->willReturn('resources');
        $formFactory->createBuilder('form', array(),array())->willReturn($formBuilder);
        $formBuilder->getForm()->willReturn($form);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ResourceContext');
    }

    function it_throw_exception_when_resource_key_is_not_resource_group_key(EventDispatcher $dispatcher,
        FormFactory $formFactory, Router $router, MapBuilder $MapBuilder, ResourceElement $ResElement, TextType $resource)
    {
        $ResElement->getKey()->willReturn('resources.resource_key');
        $MapBuilder->getMap()->willReturn(array(
            'resources' => array(
                'resource_key' => $resource
            )
        ));

        $this->shouldThrow(new ContextBuilderException("resources.resource_key its not a resource group key"))
            ->during('__construct', array($dispatcher, $ResElement, $MapBuilder, $formFactory, $router));
    }

    function it_have_form_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('form');
    }

    function it_have_element_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_handle_valid_request_with_post(Request $request, MapBuilder $builder, ResourceElement $element, TextType $textResource,
        EmailType $emailResource, FormFactory $formFactory, FormBuilder $formBuilder, ResourceRepository $repository,
        FormBuilder $textFormBuilder, FormBuilder $emailFormBuilder, Form $form, Router $router, ObjectManager $objectManager,
        EventDispatcher $dispatcher)
    {
        $element->getId()->willReturn('resource_page');
        $element->getKey()->willReturn('resources');
        $element->getResourceFormOptions()->shouldBeCalled()->willReturn(array());
        $element->getRepository()->willReturn($repository);
        $element->getObjectManager()->willReturn($objectManager);
        $objectManager->flush()->shouldBeCalled();
        $repository->get(Argument::type('string'))->willReturn(null);
        $textResource->getName()->willReturn('resources.resource_text');
        $emailResource->getName()->willReturn('resources.resource_email');

        $textResource->getFormBuilder($formFactory)->willReturn($textFormBuilder);
        $emailResource->getFormBuilder($formFactory)->willReturn($emailFormBuilder);

        $builder->getMap()->willReturn(array(
            'resources' => array(
                'resource_text' => $textResource,
                'resource_email' => $emailResource
            )
        ));

        $formFactory->createBuilder('form', array(
                'resources_resource_text' => null,
                'resources_resource_email' => null
            ),
            array()
        )->shouldBeCalled()->willReturn($formBuilder);

        $formBuilder->add($textFormBuilder, 'resource', array(
            'resource_key' => 'resources.resource_text'
        ))->shouldBeCalled();
        $formBuilder->add($emailFormBuilder, 'resource', array(
            'resource_key' => 'resources.resource_email'
        ))->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);
        $form->submit($request)->shouldBeCalled()->willReturn(true);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $form->getData()->shouldBeCalled()->willReturn(array());

        $router->generate('fsi_admin_resource', array('element' => 'resource_page'))
            ->shouldBeCalled()
            ->willReturn('/admin/resource/resource_page');

        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_FORM_REQUEST_PRE_SUBMIT,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_FORM_REQUEST_POST_SUBMIT,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_PRE_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_POST_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();
        $dispatcher->dispatch(
            ResourceEvents::RESOURCE_RESPONSE_PRE_RENDER,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldNotBeCalled();

        $this->handleRequest($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    public function getMatchers()
    {
        return array(
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        );
    }
}
