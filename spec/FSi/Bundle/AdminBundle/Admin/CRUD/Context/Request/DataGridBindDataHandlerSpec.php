<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcher $eventDispatcher, ListEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throw_exception_for_non_list_event(AdminEvent $event, Request $request)
    {
        $this->shouldThrow(
                new RequestHandlerException(
                    "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridBindDataHandler require ListEvent"
                )
            )->during('handleRequest', array($event, $request));
    }

    function it_do_nothing_when_request_is_not_a_POST(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_do_nothing_when_request_is_not_a_POST_and_return_respone_from_pre_render_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_bind_data_at_datagrid_for_POST_request(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        DataGrid $dataGrid,
        DataSource $dataSource,
        ListElement $element
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event)
            ->shouldBeCalled();

        $event->getElement()->willReturn($element);
        $element->saveDataGrid()->shouldBeCalled();
        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();
        $dataSource->getResult()->willReturn(array(1));
        $dataGrid->setData(array(1))->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_return_response_from_datagrid_pre_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_datagrid_post_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcher $eventDispatcher,
        DataGrid $dataGrid
    ) {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
