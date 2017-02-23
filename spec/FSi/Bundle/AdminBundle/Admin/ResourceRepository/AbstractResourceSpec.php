<?php

/**
 * (c) FSi sp. z o.o. <info@fsi.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\FSi\Bundle\AdminBundle\Admin\ResourceRepository;

use PhpSpec\ObjectBehavior;

class AbstractResourceSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyResource');
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
        $this->getRouteParameters()->shouldReturn([
            'element' => 'main_page',
        ]);
    }

    function it_have_route_name()
    {
        return $this->getRoute()->shouldReturn('fsi_admin_resource');
    }

    function it_has_default_options_values()
    {
        $this->getOptions()->shouldReturn([
            'template' => null
        ]);
    }
}
