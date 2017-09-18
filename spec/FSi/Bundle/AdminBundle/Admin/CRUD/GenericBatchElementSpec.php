<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use PhpSpec\ObjectBehavior;
use FSi\Bundle\AdminBundle\spec\fixtures\MyBatch;
use FSi\Bundle\AdminBundle\Admin\CRUD\GenericBatchElement;
use FSi\Bundle\AdminBundle\Admin\CRUD\BatchElement;
use FSi\Bundle\AdminBundle\Admin\Element;

class GenericBatchElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(MyBatch::class);
        $this->beConstructedWith([]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GenericBatchElement::class);
    }

    function it_is_delete_element()
    {
        $this->shouldHaveType(BatchElement::class);
    }

    function it_is_admin_element()
    {
        $this->shouldHaveType(Element::class);
    }

    function it_has_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }
}
