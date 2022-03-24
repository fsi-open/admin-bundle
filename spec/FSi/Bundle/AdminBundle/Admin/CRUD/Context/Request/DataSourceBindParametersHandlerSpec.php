<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\Context\Request\DataSourceBindParametersHandler;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Bundle\AdminBundle\Event\AdminEvent;
use FSi\Bundle\AdminBundle\Event\ListDataSourcePostBindEvent;
use FSi\Bundle\AdminBundle\Event\ListDataSourcePreBindEvent;
use FSi\Bundle\AdminBundle\Event\ListEvent;
use FSi\Bundle\AdminBundle\Exception\RequestHandlerException;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataSource\DataSourceInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DataSourceBindParametersHandlerSpec extends ObjectBehavior
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

    public function it_throws_exception_for_non_list_event(AdminEvent $wrongEvent, Request $request): void
    {
        $this->shouldThrow(
            new RequestHandlerException(sprintf("%s requires ListEvent", DataSourceBindParametersHandler::class))
        )->during('handleRequest', [$wrongEvent, $request]);
    }

    public function it_binds_request_to_datasource_and_dispatch_events(
        ListEvent $event,
        DataSourceInterface $dataSource,
        Request $request,
        EventDispatcherInterface $eventDispatcher
    ): void {
        $eventDispatcher->dispatch(Argument::type(ListDataSourcePreBindEvent::class))->shouldBeCalled();

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBeCalled();

        $eventDispatcher->dispatch(Argument::type(ListDataSourcePostBindEvent::class))->willReturn($event);

        $this->handleRequest($event, $request)->shouldReturn(null);
    }

    public function it_returns_response_from_pre_datasource_bind_parameters_event(
        ListEvent $event,
        Request $request,
        EventDispatcherInterface $eventDispatcher,
        Response $response
    ): void {
        $eventDispatcher->dispatch(Argument::type(ListDataSourcePreBindEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
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
        $eventDispatcher->dispatch(Argument::type(ListDataSourcePreBindEvent::class))->willReturn($event);

        $event->getDataSource()->willReturn($dataSource);
        $dataSource->bindParameters($request)->shouldBecalled();

        $eventDispatcher->dispatch(Argument::type(ListDataSourcePostBindEvent::class))
            ->will(
                function (array $args) use ($response) {
                    $args[0]->setResponse($response->getWrappedObject());

                    return $args[0];
                }
            );

        $this->handleRequest($event, $request)->shouldReturnAnInstanceOf(Response::class);
    }
}
