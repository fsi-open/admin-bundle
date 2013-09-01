<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class EditContextBuilderSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, Router $router, Request $request)
    {
        $this->beConstructedWith($dispatcher, $router);
        $this->setRequest($request);
        $request->get('id', null)->willReturn(1);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\EditContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(CRUDElement $element, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);

        $element->hasEditForm($entity)->shouldBeCalled()->willReturn(true);

        $this->supports('fsi_admin_crud_edit', $element)->shouldReturn(true);
    }

    function it_throws_exception_when_doctrine_crud_element_does_not_have_edit_form(CRUDElement $element,
        DoctrineDataIndexer $indexer)
    {
        $element->getName()->shouldBeCalled()->willReturn('My Element');
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);

        $element->hasEditForm($entity)->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not have edit form"))
            ->during('supports', array('fsi_admin_crud_edit', $element));
    }

    function it_handle_request_and_throws_exception_when_cant_find_entity_by_id(DoctrineDataIndexer $indexer,
        CRUDElement $element)
    {
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->willReturn(null);

        $this->shouldThrow(new InvalidEntityIdException("Cant find entity with id 1"))->during('buildContext', array($element));
    }

    function it_build_context(CRUDElement $element, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);

        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\EditContext');
    }
}
