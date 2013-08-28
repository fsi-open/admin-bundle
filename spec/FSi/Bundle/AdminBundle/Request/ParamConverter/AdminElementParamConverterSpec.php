<?php

namespace spec\FSi\Bundle\AdminBundle\Request\ParamConverter;

use FSi\Bundle\AdminBundle\Admin\Manager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AdminElementParamConverterSpec extends ObjectBehavior
{
    function let(Manager $manager)
    {
        $this->beConstructedWith($manager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('FSi\Bundle\AdminBundle\Request\ParamConverter\AdminElementParamConverter');
    }

    function it_supports_any_object_that_implements_element_interface(ParamConverter $configuration)
    {
        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\Doctrine\CRUDElement');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\CRUD\AbstractCRUD');
        $this->supports($configuration)->shouldReturn(true);

        $configuration->getClass()->willReturn('FSi\Bundle\AdminBundle\Admin\Manager');
        $this->supports($configuration)->shouldReturn(false);
    }
}
