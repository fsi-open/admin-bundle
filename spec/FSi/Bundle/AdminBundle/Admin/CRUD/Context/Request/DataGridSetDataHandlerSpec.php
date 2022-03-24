<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPostSetDataEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPreSetDataEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridSetDataHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        ListEvent $event,
        Request $request,
        ListElement $element,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource
    ): void {
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);
        $event->getDataSource()->willReturn($dataSource);
        $event->getDataGrid()->willReturn($dataGrid);

        $this->beConstructedWith($eventDispatcher);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throw_exception_for_non_list_event(AdminEvent $wrongEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridSetDataHandler requires ListEvent"
            )
        )->during('handleRequest', [$wrongEvent, $request]);
    }

    public function it_set_data_at_datagrid_and_dispatch_events(
        ListEvent $event,
        DataSourceInterface $dataSource,
        Result $result,
        DataGridInterface $dataGrid,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSetDataEvent::class))->willReturn($event);

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn($result);
        $dataGrid->setData($result)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(ListDataGridPostSetDataEvent::class))->willReturn($event);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_data(
        EventDispatcherInterface $eventDispatcher,
        ListEvent $event,
        Request $request,
        Response $response
    ): void {
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSetDataEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_datagrid_post_bind_data(
        EventDispatcherInterface $eventDispatcher,
        ListEvent $event,
        Request $request,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        Result $result,
        Response $response
    ): void {
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSetDataEvent::class))->willReturn($event);

        $event->getDataGrid()->willReturn($dataGrid);
        $event->getDataSource()->willReturn($dataSource);

        $dataSource->getResult()->willReturn($result);
        $dataGrid->setData($result)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(ListDataGridPostSetDataEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
