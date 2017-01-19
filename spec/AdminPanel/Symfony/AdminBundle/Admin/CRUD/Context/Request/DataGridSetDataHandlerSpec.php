<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class DataGridSetDataHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     */
    function let($eventDispatcher, $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_list_event($event, $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridSetDataHandler require ListEvent"
            )
        )->during('handleRequest', array($event, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @param \FSi\Component\DataGrid\DataGrid $dataGrid
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_set_data_at_datagrid_and_dispatch_events(
        $event,
        $dataSource,
        $dataGrid,
        $request,
        $eventDispatcher
    )
    {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn(array(1));
        $dataGrid->setData(array(1))->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_return_response_from_datagrid_pre_bind_data($eventDispatcher, $event, $request)
    {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \FSi\Component\DataGrid\DataGrid $dataGrid
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_return_response_from_datagrid_post_bind_data(
        $eventDispatcher,
        $event,
        $request,
        $dataGrid,
        $dataSource
    )
    {
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn(array(1));
        $dataGrid->setData(array(1))->shouldBeCalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_DATA_POST_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
