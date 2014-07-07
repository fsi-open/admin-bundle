<?php

namespace spec\FSi\Bundle\AdminBundle\Admin\CRUD;

use FSi\Bundle\AdminBundle\Exception\RuntimeException;
use FSi\Component\DataGrid\DataGridFactory;
use FSi\Component\DataSource\DataSourceFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Form\FormFactoryInterface;

class GenericBatchElementSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf('FSi\Bundle\AdminBundle\spec\fixtures\MyBatch');
        $this->beConstructedWith(array());
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
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Admin\ElementInterface');
    }

    function it_have_default_route()
    {
        $this->getRoute()->shouldReturn('fsi_admin_batch');
    }
}
