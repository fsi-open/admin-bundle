<?php

namespace spec\AdminPanel\Symfony\AdminBundle\Admin\CRUD;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericBatchElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('AdminPanel\Symfony\AdminBundle\Tests\Doubles\MyBatch');
        $this->beConstructedWith(array());
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\GenericBatchElement');
    }

    function it_is_delete_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\CRUD\BatchElement');
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType('AdminPanel\Symfony\AdminBundle\Admin\Element');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }
}
