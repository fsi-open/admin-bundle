<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository;

use PhpSpec\ObjectBehavior;

class AbstractResourceSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyResource');
    }

    public function it_have_id()
    {
        $this->getId()->shouldReturn('main_page');
    }

    public function it_have_name()
    {
        $this->getName()->shouldReturn('admin.main_page');
    }

    public function it_have_element_in_router_parameters()
    {
        $this->getRouteParameters()->shouldReturn([
            'element' => 'main_page',
        ]);
    }

    public function it_have_route_name()
    {
        return $this->getRoute()->shouldReturn('fsi_admin_resource');
    }

    public function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'template' => null
        ]);
    }
}
