<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\Doctrine\Context;

use FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement;
use FSi\Bundle\AdminBundle\Event\CRUDEvents;
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
        $element->createDataGrid()->willReturn($datagrid);
        $element->createDataSource()->willReturn($datasource);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Doctrine\Context\ListContext');
    }

    function it_is_context()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Admin\Context\ContextInterface');
    }

    function it_have_array_data(CRUDElement $element)
    {
        $element->getOption('crud_list_title')->shouldBeCalled();

        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
        $this->getData()->shouldHaveKeyInArray('datasource_view');
        $this->getData()->shouldHaveKeyInArray('element');
        $this->getData()->shouldHaveKeyInArray('title');
    }

    function it_has_template(CRUDElement $element)
    {
        $element->hasOption('template_crud_list')->willReturn(true);
        $element->getOption('template_crud_list')->willReturn('this_is_list_template.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('this_is_list_template.html.twig');
    }

    function it_handle_request_with_POST_and_return_response(
        EventDispatcher $dispatcher,
        CRUDElement $element,
        Request $request,
        DataSource $datasource,
        DataGrid $datagrid
    ) {
        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_CONTEXT_POST_CREATE,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $datasource->bindParameters($request)->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATASOURCE_REQUEST_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $datasource->getResult()->shouldBeCalled()->willReturn(array());

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATAGRID_DATA_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $datagrid->setData(array())->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATAGRID_DATA_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $request->isMethod('POST')->shouldBeCalled()->willReturn(true);

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_PRE_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $datagrid->bindData($request)->shouldBeCalled();

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_DATAGRID_REQUEST_POST_BIND,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
        )->shouldBeCalled();

        $element->saveDataGrid()->shouldBeCAlled();
        $datasource->bindParameters($request)->shouldBeCAlled();
        $datasource->getResult()->shouldBeCalled()->willReturn(array());

        $dispatcher->dispatch(
            CRUDEvents::CRUD_LIST_RESPONSE_PRE_RENDER,
            Argument::type('FSi\Bundle\AdminBundle\Event\ListEvent')
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
