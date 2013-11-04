<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
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

    function it_supports_doctrine_crud_element_that_allow_to_edit_objects(CRUDElement $element, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $element->getOption('allow_edit')->shouldBeCalled()->willReturn(true);
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);

        $this->supports('fsi_admin_crud_edit', $element)->shouldReturn(true);
    }

    function it_throws_exception_when_doctrine_crud_element_does_not_allow_edit_objects(CRUDElement $element)
    {
        $element->getName()->shouldBeCalled()->willReturn('My Element');
        $element->getOption('allow_edit')->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not allow to edit objects"))
            ->during('supports', array('fsi_admin_crud_edit', $element));
    }

    function it_throws_exception_when_cant_find_object_by_id(
        DoctrineDataIndexer $indexer,
        CRUDElement $element
    ) {
        $element->getOption('allow_edit')->shouldBeCalled()->willReturn(true);
        $element->getDataIndexer()->willReturn($indexer);
        $indexer->getData(1)->willReturn(null);

        $this->shouldThrow(new ContextBuilderException("Cant find object with id 1"))
            ->during('supports', array('fsi_admin_crud_edit', $element));
    }

    function it_build_context(CRUDElement $element, DoctrineDataIndexer $indexer)
    {
        $entity = new Entity();
        $element->getDataIndexer()->willReturn($indexer);
        $element->createForm($entity)->shouldBeCalled();
        $indexer->getData(1)->shouldBeCalled()->willReturn($entity);

        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\EditContext');
    }
}
