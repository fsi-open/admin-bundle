<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class DeleteContextSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, CRUDElement $element, Router $router, DoctrineDataIndexer $indexer,
        FormFactory $factory, Form $form, FormView $view)
    {
        $entity = new Entity();
        $entity1 = new Entity();
        $this->beConstructedWith($dispatcher, $element, $router, $factory, array($entity, $entity1));
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getIndex($entity)->willReturn(1);
        $indexer->getIndex($entity1)->willReturn(2);
        $factory->createNamed('delete', 'form')->willReturn($form);
        $form->createView()->willReturn($view);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Doctrine\DeleteContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_element_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_have_indexes_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('indexes');
    }

    function it_have_form_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('form');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_delete')->willReturn(true);
        $element->getOption('template_crud_delete')->willReturn('this_is_delete_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_delete_template.html.twig');
    }

    function it_handle_request_and_return_null(EventDispatcher $dispatcher, Request $request, ParameterBag $bag)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $bag->has('confirm')->willReturn(false);
        $bag->has('cancel')->willReturn(false);
        $request->request = $bag;

        $this->handleRequest($request)->shouldReturn(null);
    }

    function it_handle_request_with_confirm_and_return_null(EventDispatcher $dispatcher, Form $form, Request $request,
        ParameterBag $bag, CRUDElement $element, Router $router)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $bag->has('confirm')->willReturn(true);
        $bag->has('cancel')->willReturn(false);
        $request->request = $bag;

        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_FORM_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->submit($request)->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_FORM_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $form->isValid()->shouldBeCalled()->willReturn(true);

        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_ENTITIES_PRE_DELETE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $element->delete(Argument::type('spec\FSi\Bundle\AdminBundle\Admin\Context\Doctrine\Entity'))->shouldBeCalledTimes(2);

        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_ENTITIES_POST_DELETE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $element->getId()->willReturn('element_id');

        $router->generate('fsi_admin_crud_list', array(
            'element' => 'element_id'
        ))->shouldBeCalled()->willReturn('redirect_create_url');

        $this->handleRequest($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
    }

    function it_handle_request_with_cancel(EventDispatcher $dispatcher, Request $request,
        ParameterBag $bag, CRUDElement $element, Router $router)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $bag->has('confirm')->willReturn(false);
        $bag->has('cancel')->willReturn(true);
        $request->request = $bag;

        $dispatcher->dispatch(
            AdminEvents::CRUD_DELETE_FORM_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldNotBeCalled();

        $element->getId()->willReturn('element_id');

        $router->generate('fsi_admin_crud_list', array(
            'element' => 'element_id'
        ))->shouldBeCalled()->willReturn('redirect_create_url');

        $this->handleRequest($request)->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\RedirectResponse');
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
