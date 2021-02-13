<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;
use FSi\Bundle\AdminBundle\spec\fixtures\MyForm;
use FSi\Bundle\AdminBundle\Admin\CRUD\FormElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class GenericFormElementSpec extends ObjectBehavior
{
    public function let(FormFactoryInterface $factory): void
    {
        $this->beAnInstanceOf(MyForm::class);
        $this->beConstructedWith([]);
        $this->setFormFactory($factory);
    }

    public function it_is_form_element(): void
    {
        $this->shouldHaveType(FormElement::class);
    }

    public function it_is_admin_element(): void
    {
        $this->shouldHaveType(Element::class);
    }

    public function it_have_default_route(): void
    {
        $this->getRoute()->shouldReturn('fsi_admin_form');
    }

    public function it_throws_exception_when_init_form_does_not_return_form(FormFactoryInterface $factory): void
    {
        $factory->create(Argument::cetera())->willReturn(null);

        $this->shouldThrow(\TypeError::class)->during('createForm');
    }

    public function it_has_default_options_values(): void
    {
        $this->getOptions()->shouldReturn(
            [
                'template_form' => null,
                'allow_add' => true,
            ]
        );
    }
}
