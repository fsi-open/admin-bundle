<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactory;

class FormWorkerSpec extends ObjectBehavior
{
    function let(FormFactory $formFactory)
    {
        $this->beConstructedWith($formFactory);
    }

    function it_mount_form_factory_to_elements_that_are_form_aware(AbstractCRUD $element, FormFactory $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }

    function it_mount_form_factory_to_elements_that_implements_form_element(FormElement $element, FormFactory $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
