<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Event\ListEvents;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class DataSourceBindParametersHandlerSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \FSi\Bundle\AdminBundle\Event\ListEvent $event
     */
    function let($eventDispatcher, $event)
    {
        $event->hasResponse()->willReturn(false);
        $this->beConstructedWith($eventDispatcher);
    }

    function it_is_context_request_handler()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\AdminEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_for_non_list_event($event, $request)
    {
        $this->shouldThrow(
                new RequestHandlerException(
                    "FSi\\Bundle\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataSourceBindParametersHandler require ListEvent"
                )
            )->during('handleRequest', array($event, $request));
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\ListEvent $event
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_bind_request_to_datasource_and_dispatch_events(
        $event,
        $dataSource,
        $request,
        $eventDispatcher
    ) {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_POST_BIND, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_return_response_from_pre_datasource_bind_parameters_event($event, $request, $eventDispatcher)
    {
        $eventDispatcher->dispatch(ListEvents::LIST_DATASOURCE_REQUEST_PRE_BIND, $event)
            ->will(function() use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_return_response_from_post_datasource_bind_parameters_event(
        $event,
        $request,
        $eventDispatcher,
        $dataSource
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
