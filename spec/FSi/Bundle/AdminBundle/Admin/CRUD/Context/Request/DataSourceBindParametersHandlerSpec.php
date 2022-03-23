<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataSource\DataSourceInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\DataSourceBindParametersHandler;

class DataSourceBindParametersHandlerSpec extends ObjectBehavior
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
            new RequestHandlerException(sprintf("%s requires ListEvent", DataSourceBindParametersHandler::class))
        )->during('handleRequest', [$event, $request]);
    }

    public function it_binds_request_to_datasource_and_dispatch_events(
        ListEvent $event,
        DataSourceInterface $dataSource,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();

        $eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_returns_response_from_pre_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }

    public function it_returns_response_from_post_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataSourceInterface $dataSource,
        Response $response
    ): void {
        $eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch($event, ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND)
            ->will(
                function () use ($event, $response) {
                    $event->hasResponse()->willReturn(true);
                    $event->getResponse()->willReturn($response);
                }
            );

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf(Response::class);
    }
}
