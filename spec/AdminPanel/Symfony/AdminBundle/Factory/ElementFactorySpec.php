<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ElementFactorySpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     */
    public function let($productionLine)
    {
        $this->beConstructedWith($productionLine);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Factory\ProductionLine $productionLine
     */
    public function it_create_admin_element($productionLine)
    {
        $productionLine->workOn(Argument::type('AdminPanel\Symfony\AdminBundle\Admin\Element'))->shouldBeCalled();
        $this->create("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\SimpleAdminElement")
            ->shouldReturnAnInstanceOf("AdminPanel\\Symfony\\AdminBundle\\Tests\\Doubles\\Admin\\SimpleAdminElement");
    }

    public function it_throw_exception_when_class_does_not_implement_admin_element_interface()
    {
        $this->shouldThrow(new \InvalidArgumentException("StdClass does not seems to be an admin element."))
            ->during('create', ['StdClass']);
    }
}
