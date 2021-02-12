<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;

class DataGridSetDataHandlerSpec extends ObjectBehavior
{
    public function let(EventDispatcherInterface $eventDispatcher, ListEvent $event): void
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function it_throw_exception_for_non_list_event(AdminEvent $event, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridSetDataHandler requires ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    public function it_set_data_at_datagrid_and_dispatch_events(
        ListEvent $event,
        DataSourceInterface $dataSource,
        DataGridInterface $dataGrid,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_DATA_PRE_BIND)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_DATA_POST_BIND)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_data(
        EventDispatcherInterface $eventDispatcher,
        ListEvent $event,
        Request $request,
        Response $response
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_DATA_PRE_BIND)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_datagrid_post_bind_data(
        EventDispatcherInterface $eventDispatcher,
        ListEvent $event,
        Request $request,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        Response $response
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_DATA_PRE_BIND)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_DATA_POST_BIND)
            ->will(function() use ($event, $response) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn($response);
            });

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
