<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use PhpSpec\ObjectBehavior;

class GenericBatchElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyBatch');
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement');
    }

    function it_is_delete_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }
}
