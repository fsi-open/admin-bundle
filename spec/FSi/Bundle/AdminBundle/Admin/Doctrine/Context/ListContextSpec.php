<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Event\AdminEvents;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;

class ListContextSpec extends ObjectBehavior
{
    function let(EventDispatcher $dispatcher, CRUDElement $element, DataSource $datasource, DataGrid $datagrid)
    {
        $this->beConstructedWith($dispatcher, $element);
        $element->getDataGrid()->willReturn($datagrid);
        $element->getDataSource()->willReturn($datasource);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ListContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_datagrid_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
    }

    function it_have_datasource_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('datasource_view');
    }

    function it_have_element_in_data()
    {
        $this->getData()->shouldHaveKeyInArray('element');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_list')->willReturn(true);
        $element->getOption('template_crud_list')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    function it_handle_request_with_POST_and_return_response(EventDispatcher $dispatcher, CRUDElement $element,
       Request $request, DataSource $datasource, DataGrid $datagrid)
    {
        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $datasource->bindParameters($request)->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $datasource->getResult()->shouldBeCalled()->willReturn(array());

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $datagrid->setData(array())->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATAGRID_REQUEST_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $datagrid->bindData($request)->shouldBeCalled();

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_DATAGRID_REQUEST_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $element->saveDataGrid()->shouldBeCAlled();
        $datasource->bindParameters($request)->shouldBeCAlled();
        $datasource->getResult()->shouldBeCalled()->willReturn(array());

        $dispatcher->dispatch(
            AdminEvents::CRUD_LIST_RESPONSE_PRE_RENDER,
            Argument::type('FSi\Bundle\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

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
