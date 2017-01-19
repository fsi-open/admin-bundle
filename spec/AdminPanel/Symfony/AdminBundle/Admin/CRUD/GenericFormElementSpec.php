<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericFormElementSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    function let($factory)
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyForm');
        $this->beConstructedWith(array());
        $this->setFormFactory($factory);
    }

    function it_is_form_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_form');
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    function it_throw_exception_when_init_form_does_not_return_instance_of_form($factory)
    {
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('createForm');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template_form' => null,
        ));
    }
}
