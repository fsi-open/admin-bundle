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

class DataSourceBindParametersHandlerSpec extends ObjectBehavior
{
    function let(EventDispatcherInterface $eventDispatcher, ListEvent $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    function it_throws_exception_for_non_list_event(AdminEvent $event, Request $request)
    {
        $this->shouldThrow(
            new RequestHandlerException(
                "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataSourceBindParametersHandler require ListEvent"
            )
        )->during('handleRequest', [$event, $request]);
    }

    function it_binds_request_to_datasource_and_dispatch_events(
        ListEvent $event,
        DataSourceInterface $dataSource,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    function it_returns_response_from_pre_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    function it_returns_response_from_post_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        DataSourceInterface $dataSource
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
