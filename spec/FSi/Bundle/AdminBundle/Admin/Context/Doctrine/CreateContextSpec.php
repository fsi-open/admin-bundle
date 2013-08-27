<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class FormData
{

}

class CreateContextSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, CRUDElement $element, Form $form, Router $router)
    {
        $this->beConstructedWith($dispatcher, $element, $router);
        $element->getCreateForm()->willReturn($form);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Doctrine\CreateContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_form__in_data()
    {
        $this->getData()->shouldHaveKeyInArray('form');
    }

    function it_have_element__in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_create')->willReturn(true);
        $element->getOption('template_crud_create')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    function it_handle_request_with_POST_and_return_redirect_response(EventDispatcher $dispatcher, CRUDElement $element,
         Request $request, Form $form, ParameterBag $bag, FormData $data, Router $router)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);

        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_FORM_REQUEST_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->submit($request)->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_FORM_REQUEST_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->isValid()->willReturn(true);

        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_ENTITY_PRE_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->getData()->willReturn($data);
        $element->save($data)->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_ENTITY_POST_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $element->getId()->willReturn('element_id');

        $router->generate('fsi_admin_crud_list', array(
            'element' => 'element_id'
        ))->shouldBeCalled()->willReturn('redirect_create_url');

        $this->handleRequest($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_handle_request_without_POST_and_return_response(EventDispatcher $dispatcher, Request $request)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_CREATE_RESPONSE_PRE_RENDER,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(false);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function getMatchers()
    {
        return array(
            'haveKeyInArray' => function($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        );
    }
}
