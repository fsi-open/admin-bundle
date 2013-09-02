<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource;

class MyRresource extends AbstractResource
{
    public function getKey()
    {
        return 'resources.main_page';
    }

    public function getId()
    {
        return 'main_page';
    }

    public function getName()
    {
        return 'admin.main_page';
    }
}

class AbstractResourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository\MyRresource');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\ResourceRepository\AbstractResource');
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
}
