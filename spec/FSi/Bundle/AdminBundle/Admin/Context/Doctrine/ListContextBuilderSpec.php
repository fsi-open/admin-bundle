<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Context\Doctrine;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Exception\ContextBuilderException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ListContextBuilderSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher)
    {
        $this->beConstructedWith($dispatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Doctrine\ListContextBuilder');
    }

    function it_is_context_builder()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextBuilderInterface');
    }

    function it_supports_doctrine_crud_element(CRUDElement $element)
    {
        $element->hasDataGrid()->shouldBeCalled()->willReturn(true);
        $element->hasDataSource()->shouldBeCalled()->willReturn(true);
        $this->supports('fsi_admin_crud_list', $element)->shouldReturn(true);
    }

    function it_throws_exception_when_doctrine_crud_element_does_not_have_datagrid_and_datasource(CRUDElement $element)
    {
        $element->getName()->shouldBeCalled()->willReturn('My Element');
        $element->hasDataGrid()->shouldBeCalled()->willReturn(false);
        $element->hasDataSource()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not have any datagrid and datasource"))
            ->during('supports', array('fsi_admin_crud_list',$element));

        $element->hasDataGrid()->shouldBeCalled()->willReturn(false);
        $element->hasDataSource()->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(new ContextBuilderException("My Element does not have any datagrid and datasource"))
            ->during('supports', array('fsi_admin_crud_list',$element));

        $element->hasDataGrid()->shouldBeCalled()->willReturn(true);
        $element->hasDataSource()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(new ContextBuilderException("My Element does not have any datagrid and datasource"))
            ->during('supports', array('fsi_admin_crud_list',$element));
    }

    function it_build_context(CRUDElement $element)
    {
        $this->buildContext($element)->shouldReturnAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\Doctrine\ListContext');
    }
}
