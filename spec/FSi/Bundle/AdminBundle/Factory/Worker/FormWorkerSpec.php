<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use PhpSpec\ObjectBehavior;

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
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD $element
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    function it_mount_form_factory_to_elements_that_are_form_aware($element, $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Admin\CRUD\FormElement $element
     * @param \Symfony\Component\Form\FormFactory $formFactory
     */
    function it_mount_form_factory_to_elements_that_implements_form_element($element, $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
