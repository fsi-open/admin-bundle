<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\GenericCRUDElement;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Form\FormFactoryInterface;

class FormWorkerSpec extends ObjectBehavior
{
    public function let(FormFactoryInterface $formFactory): void
    {
        $this->beConstructedWith($formFactory);
    }

    public function it_mount_form_factory_to_elements_that_are_form_aware(
        GenericCRUDElement $element,
        FormFactoryInterface $formFactory
    ): void {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
