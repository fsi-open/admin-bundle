<?php

namespace spec\FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class FormEventSpec extends ObjectBehavior
{
    function let(ElementInterface $element, Request $request, Form $form)
    {
        $this->beConstructedWith($element, $request, $form);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Event\FormEvent');
    }

    function it_is_admin_event()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Event\AdminEvent');
    }
}
