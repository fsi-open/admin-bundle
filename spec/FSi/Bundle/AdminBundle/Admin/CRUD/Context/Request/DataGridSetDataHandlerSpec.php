<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

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

class DataGridSetDataHandlerSpec extends ObjectBehavior
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
                    "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridSetDataHandler require ListEvent"
                )
            )->during('handleRequest', array($event, $request));
    }

    function it_set_data_at_datagrid_and_dispatch_events(
        ListEvent $event,
        DataSource $dataSource,
        DataGrid $dataGrid,
        Request $request,
        EventDispatcher $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn(array(1));
        $dataGrid->setData(array(1))->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_return_response_from_datagrid_pre_bind_data(
        EventDispatcher $eventDispatcher,
        ListEvent $event,
        Request $request
    ){
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_return_response_from_datagrid_post_bind_data(
        EventDispatcher $eventDispatcher,
        ListEvent $event,
        Request $request,
        DataGrid $dataGrid,
        DataSource $dataSource
    ){
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn(array(1));
        $dataGrid->setData(array(1))->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
