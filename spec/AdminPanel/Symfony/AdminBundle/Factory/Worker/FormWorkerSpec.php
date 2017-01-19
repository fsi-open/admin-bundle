<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FormWorkerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    function let($formFactory)
    {
        $this->beConstructedWith($formFactory);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    function it_mount_form_factory_to_elements_that_are_form_aware($element, $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement $element
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    function it_mount_form_factory_to_elements_that_implements_form_element($element, $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
