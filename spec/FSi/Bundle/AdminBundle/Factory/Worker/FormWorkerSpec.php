<?php

namespace spec\FSi\Bundle\AdminBundle\Factory\Worker;

use FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactory;

class FormWorkerSpec extends ObjectBehavior
{
    function let(FormFactory $formFactory)
    {
        $this->beConstructedWith($formFactory);
    }

    function it_mount_datagrid_factory_to_elements_that_are_datagrid_aware(AbstractCRUD $element, FormFactory $formFactory)
    {
        $element->setFormFactory($formFactory)->shouldBeCalled();

        $this->mount($element);
    }
}
