<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\Request;

use AdminPanel\Symfony\AdminBundle\Event\ListEvents;
use AdminPanel\Symfony\AdminBundle\Exception\RequestHandlerException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;

class DataGridBindDataHandlerSpec extends ObjectBehavior
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
                "AdminPanel\\Symfony\\AdminBundle\\Admin\\CRUD\\Context\\Request\\DataGridBindDataHandler require ListEvent"
            )
        )->during('handleRequest', array($event, $request));
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_do_nothing_when_request_is_not_a_POST($event, $request, $eventDispatcher)
    {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)->shouldBeCalled();

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_do_nothing_when_request_is_not_a_POST_and_return_respone_from_pre_render_event(
        $event,
        $request,
        $eventDispatcher
    )
    {
        $request->isMethod('POST')->willReturn(false);
        $eventDispatcher->dispatch(ListEvents::LIST_RESPONSE_PRE_RENDER, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \FSi\Component\DataGrid\DataGrid $dataGrid
     * @param \FSi\Component\DataSource\DataSource $dataSource
     * @param \AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement $element
     * @throws \FSi\Component\DataSource\Exception\DataSourceException
     */
    function it_bind_data_at_datagrid_for_POST_request(
        $event,
        $request,
        $eventDispatcher,
        $dataGrid,
        $dataSource,
        $element
    )
    {
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

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     */
    function it_return_response_from_datagrid_pre_bind_request_event($event, $request, $eventDispatcher)
    {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Event\ListEvent $event
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\EventDispatcher\EventDispatcher $eventDispatcher
     * @param \FSi\Component\DataGrid\DataGrid $dataGrid
     */
    function it_return_response_from_datagrid_post_bind_request_event($event, $request, $eventDispatcher, $dataGrid)
    {
        $request->isMethod('POST')->willReturn(true);
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_PRE_BIND, $event)
            ->shouldBeCalled();

        $event->getDataGrid()->willReturn($dataGrid);
        $dataGrid->bindData($request)->shouldBeCalled();
        $eventDispatcher->dispatch(ListEvents::LIST_DATAGRID_REQUEST_POST_BIND, $event)
            ->will(function () use ($event) {
                $event->hasResponse()->willReturn(true);
                $event->getResponse()->willReturn(new Response());
            });

        $this->handleRequest($event, $request)
            ->shouldReturnAnInstanceOf('Symfony\Component\HttpFoundation\Response');
    }
}
