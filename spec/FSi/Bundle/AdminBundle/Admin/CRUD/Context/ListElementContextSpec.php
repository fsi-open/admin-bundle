<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD\Context;

use FSi\Bundle\AdminBundle\Admin\Context\Request\HandlerInterface;
use FSi\Bundle\AdminBundle\Admin\CRUD\ListElement;
use FSi\Component\DataGrid\DataGridInterface;
use FSi\Component\DataGrid\DataGridViewInterface;
use FSi\Component\DataSource\DataSourceInterface;
use FSi\Component\DataSource\DataSourceViewInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use FSi\Bundle\AdminBundle\Admin\Context\ContextInterface;
use FSi\Bundle\AdminBundle\Event\ListEvent;

class ListElementContextSpec extends ObjectBehavior
{
    public function let(
        ListElement $element,
        DataSourceInterface $datasource,
        DataSourceViewInterface $dataSourceView,
        DataGridInterface $datagrid,
        DataGridViewInterface $datagridView,
        HandlerInterface $handler
    ): void {
        $this->beConstructedWith([$handler], 'default_list');
        $element->createDataGrid()->willReturn($datagrid);
        $datagrid->createView()->willReturn($datagridView);
        $element->createDataSource()->willReturn($datasource);
        $datasource->createView()->willReturn($dataSourceView);
        $this->setElement($element);
    }

    public function it_is_context(): void
    {
        $this->shouldBeAnInstanceOf(ContextInterface::class);
    }

    public function it_has_array_data(): void
    {
        $this->getData()->shouldBeArray();
        $this->getData()->shouldHaveKeyInArray('datagrid_view');
        $this->getData()->shouldHaveKeyInArray('datasource_view');
        $this->getData()->shouldHaveKeyInArray('element');
    }

    public function it_returns_default_template_if_element_does_not_have_one(ListElement $element): void
    {
        $element->hasOption('template_list')->willReturn(false);
        $this->getTemplateName()->shouldReturn('default_list');
        $this->hasTemplateName()->shouldReturn(true);
    }

    public function it_returns_template_from_element_if_it_has_one(ListElement $element): void
    {
        $element->hasOption('template_list')->willReturn(true);
        $element->getOption('template_list')->willReturn('list.html.twig');
        $this->hasTemplateName()->shouldReturn(true);
        $this->getTemplateName()->shouldReturn('list.html.twig');
    }

    public function it_handles_request_with_request_handlers(HandlerInterface $handler, Request $request): void
    {
        $handler->handleRequest(Argument::type(ListEvent::class), $request)
            ->willReturn(null);

        $this->handleRequest($request)->shouldReturn(null);
    }

    public function it_return_response_from_handler(
        HandlerInterface $handler,
        Request $request,
        Response $response
    ): void {
        $handler->handleRequest(Argument::type(ListEvent::class), $request)
            ->willReturn($response);

        $this->handleRequest($request)
            ->shouldReturnAnInstanceOf(Response::class);
    }

    public function getMatchers(): array
    {
        return [
            'haveKeyInArray' => function ($subject, $key) {
                if (!is_array($subject)) {
                    return false;
                }

                return array_key_exists($key, $subject);
            },
        ];
    }
}
