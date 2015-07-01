<?php

namespace spec\FSi\Bundle\AdminBundle\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementFactorySpec extends ObjectBehavior
{
    /**
     * @param \FSi\Bundle\AdminBundle\Factory\ProductionLine $productionLine
     */
    function let($productionLine)
    {
        $this->beConstructedWith($productionLine);
    }

    /**
     * @param \FSi\Bundle\AdminBundle\Factory\ProductionLine $productionLine
     */
    function it_create_admin_element($productionLine)
    {
        $productionLine->workOn(Argument::type('FSi\Bundle\AdminBundle\Admin\Element'))->shouldBeCalled();
        $this->create("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement")
            ->shouldReturnAnInstanceOf("FSi\\Bundle\\AdminBundle\\spec\\fixtures\\Admin\\SimpleAdminElement");
    }

    function it_throw_exception_when_class_does_not_implement_admin_element_interface()
    {
        $this->shouldThrow(new \InvalidArgumentException("StdClass does not seems to be an admin element."))
            ->during('create', array('StdClass'));
    }
}
