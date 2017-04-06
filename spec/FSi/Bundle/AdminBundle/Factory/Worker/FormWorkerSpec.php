<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormFactoryInterface;

class FormWorkerSpec extends ObjectBehavior
{
    function let(FormFactoryInterface $formFactory)
    {
        $this->beConstructedWith($formFactory);
    }

    function it_mount_form_factory_to_elements_that_are_form_aware(
        GenericCRUDElement $element,
        FormFactoryInterface $formFactory
    ) {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }

    function it_mount_form_factory_to_elements_that_implements_form_element(
        AbstractCRUD $element,
        FormFactoryInterface $formFactory
    ) {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
