<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use AdminPanel\Symfony\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericFormElementSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function let($factory)
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyForm');
        $this->beConstructedWith([]);
        $this->setFormFactory($factory);
    }

    public function it_is_form_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\FormElement');
    }

    public function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    public function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_form');
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryInterface $factory
     */
    public function it_throw_exception_when_init_form_does_not_return_instance_of_form($factory)
    {
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(new RuntimeException("initForm should return instanceof Symfony\\Component\\Form\\FormInterface"))
            ->during('createForm');
    }

    public function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'template_form' => null,
        ]);
    }
}
