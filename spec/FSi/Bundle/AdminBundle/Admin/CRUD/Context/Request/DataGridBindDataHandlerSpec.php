<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
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

class DataGridBindDataHandlerSpec extends ObjectBehavior
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

    public function it_throws_exception_for_non_list_event(AdminEvent $event, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridBindDataHandler require ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    public function it_does_nothing_when_request_is_not_a_POST(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch($event, ListEvents::LIST_RESPONSE_PRE_RENDER)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_does_nothing_when_request_is_not_a_POST_and_return_respone_from_pre_render_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch($event, ListEvents::LIST_RESPONSE_PRE_RENDER)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_bind_data_at_datagrid_for_POST_request(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        ListElement $element
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_REQUEST_POST_BIND)->shouldBeCalled();

        $event->getElement()->willReturn($element);
        $element->saveDataGrid()->shouldBeCalled();
        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();
        $dataSource->getResult()->willReturn([1]);
        $dataGrid->setData([1])->shouldBeCalled();

        $eventDispatcher->dispatch($event, ListEvents::LIST_RESPONSE_PRE_RENDER)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_datagrid_post_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DatagridInterface $dataGrid,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND)->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATAGRID_REQUEST_POST_BIND)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
