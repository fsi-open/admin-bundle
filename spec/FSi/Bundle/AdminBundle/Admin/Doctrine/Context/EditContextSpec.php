<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class Entity
{
}

class EditContextSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, CRUDElement $element, Form $form, Router $router, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $this->beConstructedWith($dispatcher, $element, $router, $entity);
        $element->getEditForm($entity)->willReturn($form);
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getIndex($entity)->willReturn(1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\EditContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_form_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('form');
    }

    function it_have_element_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_have_id_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('id');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_edit')->willReturn(true);
        $element->getOption('template_crud_edit')->willReturn('this_is_edit_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_edit_template.html.twig');
    }

    function it_handle_request_with_POST_and_return_redirect_response(EventDispatcher $dispatcher, CRUDElement $element,
          Request $request, Form $form, ParameterBag $bag, FormData $data, Router $router)
    {
        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);

        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_FORM_REQUEST_PRE_SUBMIT,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->submit($request)->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_FORM_REQUEST_POST_SUBMIT,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->isValid()->willReturn(true);

        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_ENTITY_PRE_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->getData()->willReturn($data);
        $element->save(Argument::any())->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_ENTITY_POST_SAVE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $element->getId()->willReturn('element_id');

        $router->generate('fsi_admin_crud_list', array(
            'element' => 'element_id',
        ))->shouldBeCalled()->willReturn('redirect_list_url');

        $this->handleRequest($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_handle_request_without_POST_and_return_response(EventDispatcher $dispatcher, Request $request)
    {
        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_EDIT_RESPONSE_PRE_RENDER,
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
