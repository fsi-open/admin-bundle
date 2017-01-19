<?php


namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use PhpSpec\ObjectBehavior;

class AbstractResourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyResource');
    }

    function it_have_id()
    {
        $this->getId()->shouldReturn('main_page');
    }

    function it_have_name()
    {
        $this->getName()->shouldReturn('admin.main_page');
    }

    function it_have_element_in_router_parameters()
    {
        $this->getRouteParameters()->shouldReturn(array(
            'element' => 'main_page',
        ));
    }

    function it_have_route_name()
    {
        return $this->getRoute()->shouldReturn('fsi_admin_resource');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn(array(
            'template' => null
        ));
    }
}
