<?php

namespace spec\FSi\Bundle\AdminBundle\Event;

use FSi\Bundle\AdminBundle\Admin\ElementInterface;
use FSi\Component\DataGrid\DataGrid;
use FSi\Component\DataSource\DataSource;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

class ListEventSpec extends ObjectBehavior
{
    function let(ElementInterface $element, Request $request, DataSource $dataSource, DataGrid $dataGrid)
    {
        $this->beConstructedWith($element, $request, $dataSource, $dataGrid);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Event\ListEvent');
    }

    function it_is_admin_event()
    {
        $this->shouldBeAnInstanceOf('FSi\Bundle\AdminBundle\Event\AdminEvent');
    }
}
