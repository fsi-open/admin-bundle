<?php

namespace spec\FSi\Bundle\AdminBundle\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use FSi\Bundle\AdminBundle\Exception\InvalidEntityIdException;
use FSi\Component\DataIndexer\DoctrineDataIndexer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Router;

class DeleteContextBuilderSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, Router $router, Request $request, ParameterBag $bag, FormFactory $factory)
    {
        $this->beConstructedWith($dispatcher, $router, $factory);
        $this->setRequest($request);
        $bag->get('indexes', array())->willReturn(array(1, 2));
        $request->request = $bag;
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Context\Doctrine\DeleteContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(CRUDElement $element, Router $router, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);
        $indexer->getData(2)->shouldBeCalled()->willReturn($entity);

        $this->supports('fsi_admin_crud_delete', $element, $router)->shouldReturn(true);
    }

    function it_should_throw_exception_when_at_least_one_entity_cant_be_found(CRUDElement $element,
        DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);
        $indexer->getData(2)->shouldBeCalled()->willReturn(null);


        $this->shouldThrow(new InvalidEntityIdException("Cant find entity with id 2"))
            ->during('supports', array('fsi_admin_crud_delete', $element));
    }

    function it_should_throw_exception_when_there_are_no_indexes(CRUDElement $element,
        Request $request, ParameterBag $bag)
    {
        $this->setRequest($request);
        $bag->get('indexes', array())->willReturn(array());

        $this->shouldThrow(new ContextBuilderException("There must be at least one object to execute delete action"))
            ->during('supports', array('fsi_admin_crud_delete', $element));
    }

    function it_build_context(CRUDElement $element, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);
        $indexer->getData(2)->shouldBeCalled()->willReturn($entity);

        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Context\Doctrine\DeleteContext');
    }
}
