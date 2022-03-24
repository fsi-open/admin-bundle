<?php

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPostSubmitRequestEvent;
use FSi\Bundle\AdminBundle\Event\ListDataGridPreSubmitRequestEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListResponsePreRenderEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGridFormHandlerInterface;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\Result;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandlerSpec extends ObjectBehavior
{
    public function let(
        EventDispatcherInterface $eventDispatcher,
        DataGridFormHandlerInterface $dataGridFormHandler,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        Request $request,
        ListEvent $event,
        ListElement $element
    ): void {
        $event->getElement()->willReturn($element);
        $event->getRequest()->willReturn($request);
        $event->getDataSource()->willReturn($dataSource);
        $event->getDataGrid()->willReturn($dataGrid);

        $this->beConstructedWith($eventDispatcher, $dataGridFormHandler);
    }

    public function it_is_context_request_handler(): void
    {
        $this->shouldHaveType(HandlerInterface::class);
    }

    public function it_throws_exception_for_non_list_event(AdminEvent $wrongEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridBindDataHandler require ListEvent"
            )
        )->during('handleRequest', [$wrongEvent, $request]);
    }

    public function it_does_nothing_when_request_is_not_a_POST(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch(Argument::type(ListResponsePreRenderEvent::class))->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_does_nothing_when_request_is_not_a_POST_and_return_response_from_pre_render_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(false);
        $eventDispatcher->dispatch(Argument::type(ListResponsePreRenderEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_bind_data_at_datagrid_but_does_not_save_it_when_forms_are_not_valid(
        DataGridFormHandlerInterface $dataGridFormHandler,
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        Result $result,
        ListElement $element
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSubmitRequestEvent::class))->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGridFormHandler->submit($dataGrid, $request)->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(ListDataGridPostSubmitRequestEvent::class))->shouldBeCalled();

        $dataGridFormHandler->isValid($dataGrid)->willReturn(false);

        $event->getElement()->willReturn($element);
        $element->saveDataGrid()->shouldNotBeCalled();
        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();
        $dataSource->getResult()->willReturn($result);
        $dataGrid->setData($result)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(ListResponsePreRenderEvent::class))->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_bind_data_at_datagrid_and_save_it_for_POST_request(
        DataGridFormHandlerInterface $dataGridFormHandler,
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataGridInterface $dataGrid,
        DataSourceInterface $dataSource,
        Result $result,
        ListElement $element
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSubmitRequestEvent::class))->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGridFormHandler->submit($dataGrid, $request)->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(ListDataGridPostSubmitRequestEvent::class))->shouldBeCalled();

        $dataGridFormHandler->isValid($dataGrid)->willReturn(true);

        $event->getElement()->willReturn($element);
        $element->saveDataGrid()->shouldBeCalled();
        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();
        $dataSource->getResult()->willReturn($result);
        $dataGrid->setData($result)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(ListResponsePreRenderEvent::class))->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_return_response_from_datagrid_pre_bind_request_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSubmitRequestEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_return_response_from_datagrid_post_bind_request_event(
        DataGridFormHandlerInterface $dataGridFormHandler,
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DatagridInterface $dataGrid,
        Response $response
    ): void {
        $request->isMethod(Request::METHOD_POST)->willReturn(true);
        $eventDispatcher->dispatch(Argument::type(ListDataGridPreSubmitRequestEvent::class))->willReturn($event);

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGridFormHandler->submit($dataGrid, $request)->shouldBeCalled();
        $eventDispatcher->dispatch(Argument::type(ListDataGridPostSubmitRequestEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
